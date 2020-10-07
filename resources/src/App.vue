<template>
    <div id="pageContainer">
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <router-link class="navbar-brand" :to="{name: 'index'}">DDA Builder</router-link>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <router-link tag="li" :to="{name: 'buildList'}"><a>List</a></router-link>
                        <router-link tag="li" :to="{name: 'buildAddSelect'}"><a>Create</a></router-link>
                        <router-link tag="li" :to="{name: 'bugReportAdd'}"><a>Report Bug</a></router-link>
                        <router-link tag="li" :to="{name: 'bugReportList'}"><a>Bug Reports</a></router-link>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a class="pointer" @click="toggleDarkMode">
                                <i class="fa fa-moon-o darkSymbol"></i>
                                <span class="label label-danger betaLabel">Beta</span>
                            </a>
                        </li>
                        <li class="dropdown" v-if="false">
                            <a class="dropdown-toggle pointer">username</a>
                            <ul class="dropdown-menu">
                                <li><a href="">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <section id="main" v-if="!$router.currentRoute.meta.ignoreSection">
            <router-view class="container"></router-view>
        </section>
        <router-view v-else />

        <footer id="footer" class="navbar navbar-inverse navbar-footer">
            <div class="container">
                <ul class="nav navbar-nav">
                    <router-link tag="li" :to="{name: 'changelog'}"><a>Changelog</a></router-link>
                </ul>
            </div>
        </footer>
    </div>
</template>

<script>
export default {
    name: 'App',
    created() {
    },
    methods: {
        toggleDarkMode() {

        },
    },
};
</script>