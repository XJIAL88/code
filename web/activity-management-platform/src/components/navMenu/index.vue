<template>
    <div class="nav-menu">
        <div class="nav-main">
            <template v-for="(items,index) in menu">
                <div :key="index"
                     v-if="items.index==moduleIndex">
                    <div :class="items.index==activeIndex.split(':')[0]?'active-title':'nav-title'"
                         @click="handleNav(items)">
                        <span>{{items.label}}</span>
                    </div>
                    <div :class="item.index==activeIndex.split(':')[0]?'active-nav':'nav-item'"
                         v-for="(item,i) in items.children"
                         :key="i"
                         @click="selectHandler(item)">
                        <span>{{item.label}}</span>
                    </div>
                </div>
            </template>

        </div>
    </div>
</template>


<script>
import { mapState } from "vuex";
export default {
    props: {
        menu: {
            type: Array,
            default: []
        },
        activeIndex: String,
        moduleIndex: String
    },
    data() {
        return {};
    },
    computed: {
        ...mapState(["customerCount"])
    },
    methods: {
        selectHandler(item) {
            this.$emit("select", {
                key: item.index,
                keyPath: [this.moduleIndex, item.index],
                item
            });
        },
        handleNav(item) {
            if (item.hasOwnProperty("name")) {
                this.$router.push({
                    name: item.name
                });
            }
        }
    }
};
</script>

<style lang="scss" scoped>
.nav-menu {
    width: 200px;
    height: 100%;
    background: #202228;
    color: #fff;
    .nav-title {
        font-size: 16px;
        line-height: 36px;
        font-weight: bold;
        text-indent: 15px;
    }
    .active-title {
        font-size: 16px;
        line-height: 36px;
        font-weight: bold;
        text-indent: 15px;
        background: #006eff;
    }
    .nav-item {
        font-size: 14px;
        line-height: 34px;
        text-indent: 30px;
        cursor: pointer;
    }
    .active-nav {
        font-size: 14px;
        line-height: 34px;
        cursor: pointer;
        text-indent: 30px;
        background: #006eff;
    }
}
</style>
