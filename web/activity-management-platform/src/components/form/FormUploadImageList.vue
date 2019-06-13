<template>
    <div class="form-upload-image-list">
        <div v-for="(item, index) in fileList"
             class="img"
             :key="index">
            <div v-if="item.status==='success'">
                <img :src="item.url | formatUploadUrl | formatImage(128, 128)"
                     class="form-upload-image-img">
                <i class="close el-icon-close"
                   @click="doRemove(index)"></i>
            </div>
            <div v-else>
                <el-progress type="circle"
                             :percentage="item.percentage"
                             :status="item.status"></el-progress>
            </div>
        </div>

        <el-upload list-type="picture-card"
                   :data="{fileName:'file', fileType:type}"
                   :action="action"
                   :multiple="true"
                   :show-file-list="false"
                   :file-list="fileList"
                   :on-change="onChange"
                   :on-success="onSuccess">

            <i class="el-icon-plus"></i>
        </el-upload>
    </div>
</template>
<script>
import { UPLOAD_DOMAIN } from "@/api/config.js";

export default {
    name: "FormUploadImageList",
    props: {
        // v-model
        value: {
            type: Array,
            default: () => {
                return [];
            }
        },
        // 上传图片的类型（0：原图 1:广告图 2：商品列表 3：商品明细 4：商品分类 5:商品类别）
        type: {
            type: [Number, String],
            default: 0
        },
        // 可以上传的数量
        total: {
            type: [Number, String],
            default: 1
        },
        // 字段，如果指定，则表示使用value中的某个字段
        field: {
            type: String,
            default: ""
        },
        uploadApi: {
            type: String,
            default: ""
        }
    },
    data: function() {
        return {
            //
            input: [],
            //
            action: UPLOAD_DOMAIN + this.uploadApi,
            fileList: [],
            inited: false
        };
    },
    watch: {
        value(val) {
            if (this.inited === false) {
                this.inited = true;
                this.fileList = this.imageArray2FileList(val);
            }
            this.input = val;
        },
        input(val) {
            this.$emit("input", val);
        }
    },
    methods: {
        onChange(file, fileList) {
            fileList.forEach(item => {
                if (item.response) {
                    item.url = item.response.data;
                }
            });
            this.fileList = fileList;
        },
        onSuccess() {
            this.input = this.fileList2ImageArray(this.fileList);
        },
        doRemove(index) {
            this.fileList.splice(index, 1);
            this.input = this.fileList2ImageArray(this.fileList);
        },
        imageArray2FileList(imageArray) {
            let fileList = [];
            imageArray.forEach(item => {
                if (this.field) {
                    fileList.push({
                        percentage: 100,
                        status: "success",
                        url: item[this.field],
                        data: item
                    });
                } else {
                    fileList.push({
                        percentage: 100,
                        status: "success",
                        url: item,
                        data: item
                    });
                }
            });
            return fileList;
        },
        fileList2ImageArray(fileList) {
            let imageArray = [];
            fileList.forEach(item => {
                if (item.data) {
                    imageArray.push(item.data);
                } else if (item.response) {
                    if (this.field) {
                        let data = {};
                        data[this.field] = item.response.data;
                        imageArray.push(data);
                    } else {
                        imageArray.push(item.response.data);
                    }
                }
            });
            return imageArray;
        }
    },
    mounted() {
        if (this.value) {
            this.input = this.value;
        }
        if (this.total) {
            this.limit = parseInt(this.total.toString());
        }
    }
};
</script>
<style lang="scss">
.form-upload-image-list .el-upload {
    width: 75px;
    height: 75px;
    line-height: 75px;
}

.form-upload-image-list .el-progress {
    margin-top: 10%;
    margin-left: 10%;
    width: 80%;
    height: 80%;
}

.form-upload-image-list .el-progress .el-progress-circle {
    font-size: 0;
    width: 100% !important;
    height: 100% !important;
}
</style>
<style lang="scss" scoped>
.form-upload-image-list {
    overflow: hidden;
}

.form-upload-image-list .img {
    width: 75px;
    height: 75px;
    background-color: #fbfdff;
    border: 1px dashed #c0ccda;
    border-radius: 6px;
    overflow: hidden;
    font-size: 0;
    float: left;
    position: relative;
}

.form-upload-image-list .img {
    margin-right: 10px;
}

.form-upload-image-list .img img {
    width: 100%;
    height: 100%;
}

.form-upload-image-list .img .close {
    width: 20px;
    height: 20px;
    position: absolute;
    top: 3px;
    right: 3px;
    z-index: 1;
    font-size: 20px;
    background: rgba(0, 0, 0, 0.5);
    color: #fff;
    cursor: pointer;
}

.form-upload-image-list .upload {
    float: left;
}

.form-upload-image-list .el-icon-plus {
    position: relative;
    top: 4px;
}
</style>
