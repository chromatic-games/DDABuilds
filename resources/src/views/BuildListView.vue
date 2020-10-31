<template>
    <div>
        <!-- TODO filter -->
        <div v-if="!hideFilter">

        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th :class="getHeadlineClass('author')">
                        <router-link :to="{name: 'buildList', query: getSortQuery('author')}">{{$t('build.author')}}</router-link>
                    </th>
                    <th :class="getHeadlineClass('title')">
                        <router-link :to="{name: 'buildList', query: getSortQuery('title')}">{{$t('build.title')}}</router-link>
                    </th>
                    <th :class="getHeadlineClass('gameModeID')">
                        <router-link :to="{name: 'buildList', query: getSortQuery('gameModeID')}">{{$t('build.gameMode')}}</router-link>
                    </th>
                    <th :class="getHeadlineClass('mapID')">
                        <router-link :to="{name: 'buildList', query: getSortQuery('mapID')}">{{$t('build.map')}}</router-link>
                    </th>
                    <th :class="getHeadlineClass('difficultyID')">
                        <router-link :to="{name: 'buildList', query: getSortQuery('difficultyID')}">{{$t('build.difficulty')}}</router-link>
                    </th>
                    <th :class="getHeadlineClass('likes')" class="columnDigits">
                        <router-link :to="{name: 'buildList', query: getSortQuery('likes')}">{{$t('build.likes')}}</router-link>
                    </th>
                    <th :class="getHeadlineClass('views')" class="columnDigits">
                        <router-link :to="{name: 'buildList', query: getSortQuery('views')}">{{$t('build.views')}}</router-link>
                    </th>
                    <th :class="getHeadlineClass('date')" class="columnDate">
                        <router-link :to="{name: 'buildList', query: getSortQuery('date')}">{{$t('build.date')}}</router-link>
                    </th>
                    <th class="text-right">
                        <a class="pointer" @click="changeViewMode">
                            <i :class="{'fa-th': viewMode === 'table', 'fa-bars': viewMode === 'grid'}" class="fa"></i>
                        </a>
                    </th>
                </tr>
                </thead>
                <tbody v-if="viewMode === 'table'">
                <tr v-for="build in builds">
                    <td><router-link :to="{name: 'buildList', query: {author: build.author}}">{{build.author}}</router-link></td>
                    <td><router-link :to="{name: 'build', params: buildLinkParams(build)}">{{build.title}}</router-link></td>
                    <td><router-link :to="{name: 'buildList', query: buildListSearch({gameMode: build.gameModeName})}">{{$t('gameMode.' + build.gameModeName)}}</router-link></td>
                    <td><router-link :to="{name: 'buildList', query: buildListSearch({map: build.mapName})}">{{$t('map.' + build.mapName)}}</router-link></td>
                    <td :class="'difficulty-' + build.difficultyID"><router-link :to="{name: 'buildList', query: buildListSearch({difficulty: build.difficultyName})}">{{$t('difficulty.' + build.difficultyName)}}</router-link></td>
                    <td class="columnDigits">{{number(build.likes)}}</td>
                    <td class="columnDigits">{{number(build.views)}}</td>
                    <td class="columnDate" colspan="2">{{build.date}}</td><!-- todo date -->
                </tr>
                </tbody>
            </table>
        </div>

        <ol v-if="viewMode === 'grid'" class="buildList">
            <li v-for="build in builds">
				<div class="buildBox">
                    <i v-if="build.buildStatus !== STATUS_PUBLIC" v-b-tooltip.hover="'This build is private or unlisted and is only visible for you.'" class="fa fa-eye-slash buildUnlisted"></i>
					<div class="box128">
						<div class="buildDataContainer">
							<h3 class="buildSubject">
								<router-link :to="{name: 'build', params: buildLinkParams(build)}">{{build.title}}</router-link>
							</h3>

							<ul class="inlineList dotSeparated buildMetaData">
								<li><i class="fa fa-user"></i> <router-link :to="{name: 'buildList', query: {author: build.author}}">{{build.author}}</router-link></li>
								<li><i class="fa fa-clock-o"></i> {{build.date}}</li><!-- todo date -->
								<li><i class="fa fa-eye"></i> {{number(build.views)}}</li>
								<li><i class="fa fa-comment-o"></i> {{number(build.likes)}}</li>
								<li :class="{'text-success': build.likes > 0}">
                                    <i class="fa fa-thumbs-o-up"></i> {{(build.likes > 0 ? '+' : '') + number(build.likes)}}
                                </li>
							</ul>

							<img :src="'/assets/images/thumbnails/' + build.ID + '.png'" class="img-responsive" style="height: 200px;margin: 15px auto auto;">
						</div>
					</div>
					<div class="buildFiller"></div>
					<div class="buildFooter">
						<ul class="inlineList dotSeparated buildInformation">
							<li>
                                <i class="fa fa-map"></i> <router-link :to="{name: 'buildList', query: buildListSearch({map: build.mapName})}">{{$t('map.' + build.mapName)}}</router-link>
                            </li>
							<li>
                                <i class="fa fa-gamepad"></i> <router-link :to="{name: 'buildList', query: buildListSearch({gameMode: build.gameModeName})}">{{$t('gameMode.' + build.gameModeName)}}</router-link>
                            </li>
							<li :class="'difficulty-' + build.difficultyID">
                                <i class="fa fa-tachometer"></i> <router-link :to="{name: 'buildList', query: buildListSearch({difficulty: build.difficultyName})}">{{$t('difficulty.' + build.difficultyName)}}</router-link>
                            </li>
						</ul>
					</div>
				</div>
			</li>
        </ol>
    </div>
</template>

<script>
import axios from 'axios';
import {buildLinkParams, buildListSearch} from '../utils/build';
import number from '../utils/math/number';

export default {
    name: 'BuildListView',
    props: {
        /*viewMode: {
            type: String,
            default: 'grid',
        },*/
        hideFilter: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            builds: [],
            STATUS_PUBLIC: 1,
            viewMode: localStorage?.getItem('viewMode.' + this.$route.name) || 'grid',
        };
    },
    watch: {
        '$route.query'() {
            this.fetchList();
        },
        viewMode() {
            localStorage?.setItem('viewMode.' + this.$route.name, this.viewMode);
        },
    },
    created() {
        this.fetchList();
    },
    methods: {
        number,
        buildLinkParams,
        buildListSearch,
        getHeadlineClass(field) {
            if (this.$route.query.sortField !== field) {
                return {};
            }

            let isDesc = this.$route.query.sortOrder === 'DESC';

            return {
                DESC: isDesc,
                ASC: !isDesc,
            };
        },
        getSortQuery(field) {
            let queryOptions = { ...this.$route.query };
            if (queryOptions.sortField === field) {
                queryOptions.sortOrder = queryOptions.sortOrder === 'DESC' ? 'ASC' : 'DESC';
            }
            queryOptions.sortField = field;

            return queryOptions;
        },
        fetchList() {
            let queryParams = (new URLSearchParams(this.$route.query)).toString();
            axios.get('/builds/' + (queryParams ? '?' + queryParams : '')).then(({ data }) => {
                this.builds = data.data;
            });
        },
        changeViewMode() {
            this.viewMode = this.viewMode === 'grid' ? 'table' : 'grid';
        },
    },
};
</script>