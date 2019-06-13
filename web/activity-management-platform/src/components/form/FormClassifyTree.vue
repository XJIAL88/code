<template>
    <div class="form-classify-tree">
        <el-cascader clearable
                     v-model="classifyArray"
                     :options="classifyTree"
                     :props="classifyProps"
                     @change="onChange"
                     @active-item-change="getClassify"></el-cascader>
    </div>
</template>
<script>
import api from "@/api";
import { classifyGetParentList } from "@/utils";

export default {
    name: "FormClassifyTree",
    props: {
        // v-model
        value: {
            type: [String, Number],
            default: null
        },
        level: {
            type: [Number, String]
        }
    },
    data: function() {
        return {
            //
            input: "",
            //
            classifyArray: [],
            classifyTree: [],
            classifyProps: {
                label: "name",
                value: "id",
                children: "children"
            }
        };
    },
    watch: {
        value(val) {
            if (this.classifyTree && val) {
                this.classifyArray = classifyGetParentList(
                    val,
                    this.classifyTree
                );
            } else if (!val) {
                this.classifyArray = [];
            }

            this.input = val;
        },
        input(val) {
            this.$emit("input", val);
        },
        level(val) {
            this.getData(0, res => {
                if (val > 2) {
                    res.data.dataList.forEach(item => {
                        item.children = [];
                    });
                }
                this.classifyTree = res.data.dataList;
                this.input = this.value;
            });
        }
    },
    methods: {
        onChange(val) {
            this.input = val[val.length - 1];
            this.$emit("update:classifyArray", val);
            this.$emit("change", val);
        },
        getLevel1ClassifyIndex(level1ClassifyUuid) {
            if (level1ClassifyUuid) {
                for (
                    let index = 0, len = this.classifyTree.length;
                    index < len;
                    index++
                ) {
                    if (this.classifyTree[index].id === level1ClassifyUuid) {
                        return index;
                    }
                }
            }
        },
        getLevel2ClassifyIndex(level1ClassifyIndex, level2ClassifyUuid) {
            if (level2ClassifyUuid) {
                for (
                    let index = 0,
                        len = this.classifyTree[level1ClassifyIndex].children
                            .length;
                    index < len;
                    index++
                ) {
                    if (
                        this.classifyTree[level1ClassifyIndex].children[index]
                            .id === level2ClassifyUuid
                    ) {
                        return index;
                    }
                }
            }
        },
        getClassify(classifyUuidArr) {
            let level1ClassifyUuid = "";
            let level2ClassifyUuid = "";
            let level1Index = "";
            let level2Index = "";
            if (classifyUuidArr.length) {
                level1ClassifyUuid = classifyUuidArr[0];
                level1Index = this.getLevel1ClassifyIndex(level1ClassifyUuid);
                if (level1Index === "") {
                    this.$alert("找不到一级分类索引", "错误", {
                        type: "error"
                    });
                    return;
                }
                if (classifyUuidArr.length === 2) {
                    level2ClassifyUuid = classifyUuidArr[1];
                    level2Index = this.getLevel2ClassifyIndex(
                        level1Index,
                        level2ClassifyUuid
                    );
                    if (level2Index === "") {
                        this.$alert("找不到二级分类索引", "错误", {
                            type: "error"
                        });
                        return;
                    }
                }
            } else {
                return;
            }
            this.setData(level1Index, level2Index);
        },
        setData(level1Index, level2Index) {
            if (typeof level1Index === "number" && level1Index >= 0) {
                if (level2Index === "") {
                    let level1Classify = this.classifyTree[level1Index];
                    let children = level1Classify.children;
                    if (!children || !children.length) {
                        this.getData(level1Classify.id, res => {
                            if (this.level > 3) {
                                res.data.dataList.forEach(item => {
                                    item.children = [];
                                });
                            }

                            level1Classify.children = res.data.dataList;
                        });
                    }
                } else if (
                    typeof level2Index === "number" &&
                    level2Index >= 0
                ) {
                    let level2Classify = this.classifyTree[level1Index]
                        .children[level2Index];
                    let children = level2Classify.children;
                    if (!children || !children.length) {
                        this.getData(level2Classify.id, res => {
                            if (this.level > 4) {
                                res.data.dataList.forEach(item => {
                                    item.children = [];
                                });
                            }
                            level2Classify.children = res.data.dataList;
                        });
                    }
                }
            }
        },
        getData(pid, callback) {
            api.post("getPowerListByPid", {
                pid
            }).then(res => {
                callback && callback(res);
            });
        }
    },
    mounted() {
        // this.getData("", res => {
        //     res.data.dataList.forEach(item => {
        //         item.children = [];
        //     });
        //     this.classifyTree = res.data.dataList;
        //     this.input = this.value;
        // });
    }
};
</script>
<style lang="scss">
</style>
<style lang="scss" scoped>
.form-classify-tree {
    width: 100%;
    .el-cascader {
        width: 100%;
    }
}
</style>
