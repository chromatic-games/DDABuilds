<template>
	<div class="container">
		<h3>next</h3>
		<ul>
			<li><span class="badge badge-success">Added</span> Checkbox for the rifted mode.</li>
			<li><span class="badge badge-success">Added</span> The first build wave can be renamed.</li>
			<li><span class="badge badge-success">Added</span> GitHub Repository link to footer.</li>
			<li><span class="badge badge-success">Added</span> German Localization (Beta).</li>
			<li><span class="badge badge-success">Added</span> Author, map, game mode and difficulty links in table view mode.</li>
			<li><span class="badge badge-success">Added</span> Multiple map selection in build list filter.</li>
			<li><span class="badge badge-success">Added</span> Reset option in build list, to reset all filters.</li>
			<li><span class="badge badge-success">Added</span> Difficulty colors in build add view.</li>
			<li><span class="badge badge-success">Added</span> Comments are visible in editor mode.</li>
			<li><span class="badge badge-warning">Changed</span> Updated design view for the build view.</li>
			<li><span class="badge badge-warning">Changed</span> Updated design (changed Bootstrap version to 4.x).</li>
			<li><span class="badge badge-warning">Changed</span> Updated medium color for a better readability in light mode.</li>
			<li><span class="badge badge-warning">Changed</span> Updated the require attributes view.</li>
			<li><span class="badge badge-warning">Changed</span> Dark Mode for input fields and text editor.</li>
			<li><span class="badge badge-danger">Fixed</span> Towers cant be placed anymore out of the map.</li>
			<li><span class="badge badge-danger">Fixed</span> New towers from disabled heros are no more visible.</li>
			<li><span class="badge badge-danger">Fixed</span> Fixed a memory leak in build edit mode.</li>
		</ul>
		<h3>2020-09-22</h3>
		<ul>
			<li><span class="badge badge-warning">Changed</span> New design for the create build page.</li>
			<li><span class="badge badge-danger">Fixed</span> Defense Units for Lava Mines on Difficulty Insane or higher.</li>
			<li><span class="badge badge-danger">Fixed</span> Footer Margin was incorrect in Dark Mode.</li>
		</ul>
		<h3>2020-08-20</h3>
		<ul>
			<li><span class="badge badge-success">Added</span> Series EV-A.</li>
			<li><span class="badge badge-success">Added</span> Difficulty Colors.</li>
		</ul>
		<h3>2020-07-25</h3>
		<ul>
			<li><span class="badge badge-success">Added</span> Liked build list.</li>
			<li><span class="badge badge-success">Added</span> Delete build button.</li>
			<li><span class="badge badge-success">Added</span> Disable towers where not enough defense units are available.</li>
			<li><span class="badge badge-success">Added</span> Report feature/bug page.</li>
			<li><span class="badge badge-success">Added</span> Favorite builds.</li>
			<li><span class="badge badge-success">Added</span> Dark mode (beta).</li>
			<li><span class="badge badge-warning">Changed</span> Marked active menu items.</li>
			<li><span class="badge badge-warning">Changed</span> Changed the build grid list design.</li>
			<li><span class="badge badge-warning">Changed</span> The pagination are centered.</li>
			<li><span class="badge badge-warning">Changed</span> Moved changelog link to footer.</li>
			<li><span class="badge badge-warning">Changed</span> Reduced mouse wheel rotation speed.</li>
			<li><span class="badge badge-danger">Fixed</span> Hardcore/AFK Able checkboxes have not saved the correctly value.</li>
			<li><span class="badge badge-danger">Fixed</span> Hide empty "Exp Per run" and "Time Per Run".</li>
			<li><span class="badge badge-danger">Fixed</span> Disable tower rotation with mouse wheel for auras/traps.</li>
			<li><span class="badge badge-danger">Fixed</span> Wrong save for required stats in the build.</li>
			<li><span class="badge badge-danger">Removed</span> Old Notification Link.</li>
		</ul>
		<h3>2020-05-25</h3>
		<ul>
			<li><span class="badge badge-success">Added</span> Filter for game mode in build list.</li>
			<li><span class="badge badge-success">Added</span> Table header sort indicator (arrow up/down for asc/desc).</li>
			<li><span class="badge badge-danger">Fixed</span> Scroll to top button in create map view was missing.</li>
		</ul>
		<h3>2020-05-24</h3>
		<ul>
			<li><span class="badge badge-warning">Changed</span> Replaced the new notification notice with a bell on top right.</li>
			<li><span class="badge badge-danger">Fixed</span> Defense Unit calculation on first page view for builds with multiple wave tabs.</li>
		</ul>
		<h3>2020-05-23</h3>
		<ul>
			<li><span class="badge badge-success">Added</span> A text was added if no notifications are available.</li>
			<li><span class="badge badge-success">Added</span> Towers can now be rotated with the mouse wheel (shift for slower and ctrl for faster rotation).</li>
			<li><span class="badge badge-success">Optimized</span> Page loading speed.</li>
			<li><span class="badge badge-success">Optimized</span> images in description box have a max width.</li>
			<li><span class="badge badge-warning">Changed</span> The description box is hidden if none exists.</li>
			<li><span class="badge badge-warning">Changed</span> Author's steam profile would be opened in a new tab.</li>
			<li><span class="badge badge-warning">Changed</span> Moved delete wave button to the wave tab menu.</li>
			<li><span class="badge badge-warning">Changed</span> Replaced input field for custom waves with prompt dialog.</li>
			<li><span class="badge badge-warning">Changed</span> Removed the checkbox in tower disable selection.</li>
			<li><span class="badge badge-warning">Changed</span> The newest comment is now at the top.</li>
			<li><span class="badge badge-warning">Changed</span> The box to create a comment is now above the comments.</li>
			<li><span class="badge badge-danger">Fixed</span> Some xss security vulnerabilities.</li>
			<li><span class="badge badge-danger">Fixed</span> Grid view on my builds page.</li>
			<li><span class="badge badge-danger">Fixed</span> Comments tab only visible in view or edit mode.</li>
			<li><span class="badge badge-danger">Fixed</span> Page was not be reloaded after posting the second comment.</li>
			<li><span class="badge badge-danger">Fixed</span> Author with special characters in search breaks the search after initial search.</li>
		</ul>
	</div>
</template>

<script>
export default {
	name: 'ChangelogView',
};
</script>