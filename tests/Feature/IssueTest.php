<?php

namespace Tests\Feature;

use App\Models\Issue;
use Tests\TestCase;

class IssueTest extends TestCase {
	/** @var Issue */
	public static $issue;

	public function createIssue(&$title = null, &$description = null) {
		$title = $title ?? implode(' ', $this->faker->words);
		$description = $description ?? $this->faker->text;

		return $this->postJson('/api/issues/', [
			'title' => $title,
			'description' => $description,
		]);
	}

	public function testUnauthenticated() {
		$response = $this->createIssue();
		$response->assertForbidden();
	}

	public function testInvalidInput() {
		$this->loginAsTester();

		$title = '   x';
		$response = $this->createIssue($title);
		$response->assertStatus(422);
	}

	public function testIssueCreate() {
		$this->loginAsTester();

		// delete old issues to prevent the spam protection
		Issue::query()->where('steamID', $this->getTestUser()->ID)->delete();

		// create issue
		$response = $this->createIssue($title, $description);
		$response->assertCreated();

		// fetch the issue from response
		$jsonResponse = json_decode($response->getContent(), true);
		$this->assertArrayHasKey('ID', $jsonResponse);
		$issue = Issue::find($jsonResponse['ID']);
		$this->assertNotNull($issue);

		// check issue values
		$this->assertEquals($this->getTestUser()->ID, $issue->steamID);
		$this->assertEquals($title, $issue->title);
		$this->assertEquals($description, $issue->description);
		$this->assertEquals(Issue::STATUS_OPEN, $issue->status);

		self::$issue = $issue;
	}

	/**
	 * @depends testIssueCreate
	 */
	public function testIssueCreateSpamProtection() {
		$this->loginAsTester();

		$response = $this->post('/api/issues/', [
			'title' => 'title',
			'description' => 'description',
		]);

		$response->assertOk();
		$jsonResponse = json_decode($response->getContent(), true);
		$this->assertArrayHasKey('needWait', $jsonResponse);
	}

	/**
	 * @depends testIssueCreate
	 */
	public function testIssueViewForbidden() {
		$response = $this->get('/api/issues/'.self::$issue->reportID);
		$response->assertForbidden();
	}

	/**
	 * @depends testIssueCreate
	 */
	public function testIssueViewForbiddenOthers() {
		self::$issue->update([
			'steamID' => $this->getTestUser()->ID - 1,
		]);

		$this->loginAsTester();
		$response = $this->get('/api/issues/'.self::$issue->reportID);
		$response->assertForbidden();

		self::$issue->update([
			'steamID' => $this->getTestUser()->ID,
		]);
	}

	/**
	 * @depends testIssueCreate
	 */
	public function testIssueView() {
		$this->loginAsTester();
		$response = $this->get('/api/issues/'.self::$issue->reportID);
		$response->assertOk();
	}

	public function testIssueList() {
		// guests should not have permission
		$response = $this->get('/api/issues/');
		$response->assertForbidden();

		// non maintainer should not have permission
		$this->loginAsTester();
		$response = $this->get('/api/issues/');
		$response->assertForbidden();
	}
}
