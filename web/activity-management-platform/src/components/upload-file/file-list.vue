<template>
    <div class="file-list">
        <div class="file-item" v-for="(file,index) in fileList" :key="index">
            <FilePlaceholder :file="file"/>
            <div class="options">
                <div class="buttons" @click.stop>
                    <el-button class="button button-preview" icon="el-icon-zoom-in" type="text" @click.stop="handlePreview(file)"/>
                    <el-button v-if="isRemove" class="button button-remove" icon="el-icon-delete" type="text" @click.stop="handleRemove(index)"/>
                </div>
            </div>
        </div>
        <FilePreviewDialog :visible.sync="dialogPreview"
                           :title="dialogFileName"
                           :file-src="dialogFileUrl"
        />
    </div>
</template>

<script>
    import FilePlaceholder from './file-placeholder'
    import FilePreviewDialog from './file-preview-dialog'

    export default {
        name: "file-list",
        components:{FilePlaceholder,FilePreviewDialog},
        props:{
            fileList:{
                type:Array,
                default:()=>[]
            },
            isRemove:{
                type:Boolean,
                default:true,
            },
        },
        data(){
            return {
                dialogPreview: false,
                dialogFileUrl: '',
                dialogFileName: '',
            }
        },
        methods:{
            handlePreview(file) {
                this.dialogPreview = true;
                this.dialogFileUrl = file.src;
                this.dialogFileName = file.name;
            },
            handleRemove(index) {
                this.$emit('remove', index);
            },
        }
    }
</script>

<style scoped lang="scss">
.file-list {
    display: inline-block;
    vertical-align: top;
    .file-item {
        position: relative;
        display: inline-block;
        margin-right: 20px;
        margin-bottom: 20px;
        border-radius: 6px;
        vertical-align: top;
        text-align: center;
        line-height: normal;

        .edit-progress {
            position: absolute;
            top: 0;
            left: 50%;
            margin-left: -50px;
            background: rgba(0, 0, 0, .5);
            /deep/ .el-progress__text {
                color: rgb(32, 160, 255);
            }
        }
        .options {
            display: none;
            position: absolute;
            width: 136px;
            height: 100px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0);
            transition: all ease-out 300ms;
            border-radius: 6px;
        }

        .buttons {
            @include flex-center;
            width: 100%;
            padding: 0 10px;
            box-sizing: border-box;
            line-height: normal;
            .button {
                color: #fff;
                font-size: 20px;
            }
            .button-remove {
                margin-left: 20px;
            }
        }
        &:hover {
            .options {
                @include flex-center;
                background: rgba(0, 0, 0, .5);
            }

        }

    }

}
</style>