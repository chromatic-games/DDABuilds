<template>
    <div class="container">
        <table class="table table-bordered">
            <thead>
            <tr>
                <!-- TODO i18n -->
                <td v-if="showActionColumn" style="width:1%;">Action</td>
                <td style="width:15%;">Created</td>
                <td>Title</td>
                <td style="width:15%;">Status</td>
            </tr>
            </thead>
            <tbody>
            <tr v-for="issue in issues">
                <td v-if="showActionColumn">
                    <button v-if="issue.status !== 2" class="btn btn-primary btn-close" @click="closeIssue(issue)">
                        <i class="fa fa-lock"></i>
                    </button>
                </td>
                <td>{{issue.time}}</td> <!-- TODO date -->
                <td>
                    <router-link :to="issue.link">{{issue.title}}</router-link>
                </td>
                <td>{{$t('issue.status.' + (issue.status === 2 ? 'closed' : 'open'))}}</td>
            </tr>
            </tbody>
        </table>

        <app-pagination :page="page" :pages="pages" />
    </div>
</template>

<script>
import axios from 'axios';
import AppPagination from '../../components/AppPagination';
import {closeIssue} from '../../utils/issue';
import {formatSEOTitle} from '../../utils/string';

export default {
    name: 'IssueListView',
    components: { AppPagination },
    data() {
        return {
            pages: 0,
            page: 0,
            issues: [],
        };
    },
    created() {
        this.fetchList();
    },
    watch: {
        '$route.params.page'() {
            this.fetchList();
        },
    },
    computed: {
        showActionColumn() {
            return !this.$options.mineList;
        },
    },
    methods: {
        closeIssue(issue) {
            closeIssue(issue);
        },
        fetchList() {
            let mineList = this.$options.mineList || false;
            let page = this.$route.params.page || 0;
            axios.get('/issues/?page=' + page + (mineList ? '&mine=1' : ''))
                 .then(({ data: { data, last_page, current_page } }) => {
                     for (let issue of data) {
                         issue.link = {
                             name: 'issue',
                             params: {
                                 id: issue.ID,
                                 title: formatSEOTitle(issue.title),
                             },
                         };
                     }

                     this.issues = data;
                     this.pages = last_page;
                     this.page = current_page;

                     if (this.page > this.pages) {
                         // this.pages = 0;
                         // this.page = 0;
                     }
                 }).catch(() => {
                // TODO error handling
            });
        },
    },
};
</script>