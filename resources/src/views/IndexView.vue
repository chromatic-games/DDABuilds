<template>
    <section id="main">
        <header class="index-header-image" :style="{backgroundImage: 'url(' + headerImage + ')'}"></header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="section-heading">Welcome to DD:A Builder!</h1>
                    <p class="lead">Either browse through the existing builds or log in via Steam and create your own to share them with the world.</p>
                    <p>
                        DD:A Builder site originally created by Chakratos at
                        <a href="https://dundefplanner.com" target="_blank">dundefplanner.com</a> for Dungeon Defender 1 builds.
                    </p>
                </div>
            </div>

            <template v-if="contributors.length">
                <h3 class="marginTop">Contributors</h3>
                <div v-for="contributor in contributors">
                    <img :src="contributor.avatar_url" style="width: 64px; height:64px;" /><br />
                    <a :href="contributor.html_url" target="_blank">@{{contributor.login}}</a>
                </div>
            </template>
        </div>
    </section>
</template>

<script>
import axios from 'axios';

export default {
    name: 'IndexView',
    test: 123,
    data() {
        let imageCount = 1;

        return {
            imageNumber: Math.floor((Math.random() * imageCount) + 1),
            contributors: [],
        };
    },
    created() {
        axios.get('https://api.github.com/repos/RefreshingWater/DDABuilds/contributors').then(({ data }) => {
            this.contributors = data;
        });
    },
    computed: {
        headerImage() {
            return '/assets/images/index/' + this.imageNumber + '.jpg';
        },
    },
};
</script>