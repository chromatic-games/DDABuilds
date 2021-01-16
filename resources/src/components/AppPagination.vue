<template>
    <ul v-if="pages > 1" class="pagination">
        <li v-for="page in availablePages" :class="{active: !page.to}" class="page-item">
            <router-link v-if="page.to" :to="page.to" class="page-link">{{page.number}}</router-link>
            <span v-else class="page-link">{{page.number}}</span>
        </li>
    </ul>
</template>

<script>
export default {
    name: 'AppPagination',
    props: {
        pages: {
            type: Number,
            default: 1,
            required: true,
            validator(value) {
                return value >= 0;
            },
        },
        page: {
            type: Number,
            default: 1,
            validator(value) {
                return value >= 0;
            },
        },
        routeName: {
            type: String,
            default() {
                return this.$route.name;
            },
        },
        routeParams: {
            type: Object,
            default() {
                return {};
            },
        },
    },
    computed: {
        availablePages() {
            let pages = [];
            for (let i = 0; i < this.pages; i++) {
                let pageNumber = i + 1;
                pages.push({
                    number: pageNumber,
                    to: pageNumber !== this.page ? {
                        name: this.routeName,
                        params: Object.assign({}, this.routeParams, {
                            page: pageNumber,
                        }),
                    } : null,
                });
            }

            return pages;
        },
    },
};
</script>