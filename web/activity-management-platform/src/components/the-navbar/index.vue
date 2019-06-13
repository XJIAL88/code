<template>
    <div class="navbar">
        <!-- <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item v-for="(item,index) in routes"
                                :to="item.path ? {path: item.path} : {name: item.name}">{{item.title}}
            </el-breadcrumb-item>
        </el-breadcrumb>-->
        <el-breadcrumb class="self-elementui-breadcrumb" separator-class="el-icon-arrow-right">
            <template v-for="(item, i) in expandBreadData">
                <el-breadcrumb-item :key="i" v-if="item.path" :to="{path: item.path}">{{item.label}}</el-breadcrumb-item>
                <el-breadcrumb-item :key="i" v-else>{{item.label}}</el-breadcrumb-item>
            </template>
        </el-breadcrumb>
        <div class="breadcrumb-action">
            <bread-crumb-action :renderDom="renderElement"></bread-crumb-action>
        </div>
    </div>
</template>
<script>
import { mapState, mapMutations } from "vuex";
export default {
    name: "TheNavbar",
    data() {
        return {
            routerPaths: [],
            routes: []
        };
    },
    computed: {
        ...mapState([
            "user",
            "activeIndex",
            "navData",
            "expandBreadData",
            "currentRoute"
        ])
    },
    mounted() {
        this.routes = this.$route.meta.router;
    },
    watch: {
        $route(to, from) {
            this.routes = this.$route.meta.router;
        }
    }
};
</script>
<style lang="scss" scoped>
.navbar {
    padding: 1.67rem 2.5rem;
    border-bottom: 1px solid #dee3e5;
    color: #333b46;
    font-size: 1.167rem;
}
</style>
