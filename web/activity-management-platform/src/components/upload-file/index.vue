<template>
    <div class="upload-file">
        <FileList :file-list="uploadList" :isRemove="isRemove" @remove="onRemove"/>
        <template v-if="isButton">
            <template v-if="button">
                <el-upload
                        v-if="!isUploading && (limit ? uploadList.length < limit : true)"
                        class="upload-component"
                        list-type="picture-card"
                        :accept="accept"
                        :action="action"
                        :data="uploadParams"
                        :limit="limit"
                        :show-file-list="false"
                        :before-upload="beforeUpload"
                        :on-progress="onProgress"
                        :on-success="onSuccess"
                        :on-error="onError"
                >
                    <div class="button-upload">
                        <i class="el-icon-plus"></i>
                        <span class="text">点此上传</span>
                    </div>
                </el-upload>
            </template>
            <div class="upload-add" v-else @click="$emit('upload')">
                <div class="el-upload el-upload--picture-card">
                    <div class="button-only-click">
                        <i class="el-icon-plus"></i>
                        <span class="text">点此上传</span>
                    </div>
                </div>
            </div>
        </template>
        <el-progress v-show="isUploading" :width="100" type="circle" :percentage="uploadPercent"></el-progress>
    </div>
</template>

<script>
    import {API_CONFIG} from '@/api/config'
    import {getUserId, getUserToken} from "@/utils";
    import FileList from './file-list'

    const userId = getUserId();
    const userToken = getUserToken();

    export default {
        name: "upload-file",
        components: {
            FileList,
        },
        props: {
            fileList: {
                type: Array,
                default: () => []
            },
            accept: {
                type: String,
                default: '*',
            },
            limit: {
                type: Number,
            },
            button: {
                type: Boolean,
                default: true,
            },
            isButton: {
                type: Boolean,
                default: true,
            },
            isRemove: {
                type: Boolean,
                default: true,
            },
            url: {
                type: String,
                default: 'uploadFileForm',
            },
            params: {
                type: Object,
                default: () => {
                },
            }
        },
        watch: {
            url(val) {
                this.action = API_CONFIG.baseURL + val;
            },
            params(val) {
                this.uploadParams = {...this.uploadParams, ...val};
            },
            fileList(val) {
                this.uploadList = val;
            },
            uploadList(val) {
                this.$emit('update:fileList', val);
            }
        },
        mounted() {
            this.action = API_CONFIG.baseURL + this.url;
            this.uploadParams = {...this.uploadParams, ...this.params};
            this.uploadList = this.fileList;
        },
        data() {
            return {
                action: API_CONFIG.baseURL + this.url,
                uploadParams: {
                    userId,
                    userToken,
                    fileName: 'file',
                },
                isUploading: false,
                uploadPercent: 0,
                uploadList: [],
            }
        },
        methods: {
            beforeUpload(file) {
                if (this.accept === 'application/pdf') {
                    const isPdf = file.type === 'application/pdf';
                    if(!isPdf) this.$message.error('上传报告只能是 PDF 格式!');
                    return isPdf;
                }else {
                    return true;
                }
            },
            onProgress(event, file, fileList) {
                this.isUploading = true;
                this.uploadPercent = parseFloat(file.percentage.toFixed(0));
            },
            onSuccess(response, file, fileList) {
                this.isUploading = false;
                if (response.code === 0) {
                    if (typeof response.data === 'string') {
                        const src = response.data;
                        const name = file.name;
                        this.uploadList.push({
                            src,
                            name,
                            desc: '',
                        });
                        this.$emit('success', {src, name, desc: ''});
                    } else {
                        const data = response.data;
                        const name = file.name;
                        const src = data.src;
                        this.uploadList.push({
                            src,
                            name,
                            desc: '',
                        });
                        this.$emit('success', response.data);
                    }
                    this.$emit('updateLength', this.uploadList.length);
                } else {
                    if (response.message) {
                        this.$alert(response.message + `(${response.code})`, {
                            type: 'error',
                        })
                    } else {
                        this.$alert(`未知错误${JSON.stringify(response)}`, {
                            type: 'error',
                        });
                    }
                }
            },
            onError(err, file, fileList) {
                fileList.pop();
                this.uploadList.pop();
                this.isUploading = false;
                this.$alert(JSON.stringify(err), {type: 'error',});
            },
            onRemove(index) {
                this.uploadList.splice(index, 1);
                this.$emit('updateLength', this.uploadList.length);
            },
        }

    }
</script>

<style scoped lang="scss">
    .upload-file {
        .upload-add {
            display: inline-block;
            vertical-align: top;
        }
        /deep/ .el-upload--picture-card {
            width: 136px;
            height: 100px;
            line-height: 100px;
        }
        .button-only-click {
            @include flex-center;
            flex-direction: column;
            height: 100%;
            color: #8c939d;
            .text {
                line-height: normal;
            }
        }
        .upload-component {
            display: inline-block;
            vertical-align: top;
        }
        .button-upload {
            @include flex-center;
            flex-direction: column;
            height: 100%;
            color: #8c939d;
            .text {
                line-height: normal;
            }
        }
    }
</style>