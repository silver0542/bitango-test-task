<template>
    <div>
        <v-app>
            <v-app-bar dark app>
                <v-app-bar-nav-icon
                    @click.stop="drawer = !drawer"
                ></v-app-bar-nav-icon>
                <v-toolbar-title>{{ pageTitle }}</v-toolbar-title>
            </v-app-bar>
            <v-navigation-drawer v-model="drawer" absolute bottom temporary>
                <v-list nav dense>
                    <v-list-item-group
                        active-class="deep-purple--text text--accent-4"
                    >
                        <router-link to="/">
                            <v-list-item>
                                <v-list-item-title>
                                    Home Page
                                </v-list-item-title>
                            </v-list-item>
                        </router-link>
                        <router-link to="/registration">
                            <v-list-item>
                                <v-list-item-title>
                                    Registration
                                </v-list-item-title>
                            </v-list-item>
                        </router-link>
                    </v-list-item-group>
                </v-list>
            </v-navigation-drawer>
            <v-main>
                <router-view></router-view>
            </v-main>
        </v-app>
    </div>
</template>
<script>
export default {
    name: "App",
    data() {
        return {
            drawer: false,
        };
    },
    computed: {
        pageTitle() {
            return this.$route.name;
        },
    },
};
</script>
