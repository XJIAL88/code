<template>
    <div class="file-placeholder">
        <div class="file-icon">
            <img class="icon" v-if="image[getFileSuffix(file.src)]" :src="file.src | formatUploadUrl" alt="">
            <img class="icon" v-if="getFileSuffix(file.src) === 'pdf'" src="./pdf.svg" alt="">
            <img class="icon" v-if="word[getFileSuffix(file.src)]" src="./doc.svg" alt="">
            <img class="icon" v-if="excel[getFileSuffix(file.src)]" src="./excel.svg" alt="">
        </div>
        <p class="p file-name" v-if="!image[getFileSuffix(file.src)]" :title="file.name">{{file.name}}</p>
        <p class="p file-date" v-if="file.desc && !image[getFileSuffix(file.src)]" :title="`查询日期：${file.desc}`">
            查询日期：{{file.desc}}</p>
    </div>
</template>

<script>
    import {getFileSuffix} from '@/utils'

    export default {
        name: "file-placeholder",
        props: {
            file: {
                type: Object,
                default: () => {
                    return {}
                }
            }
        },
        data() {
            return {
                image: {'jpg': true, 'jpeg': true, 'png': true},
                office: {'doc': true, 'docx': true, 'xls': true, 'xlsx': true, 'csv': true},
                word: {'doc': true, 'docx': true},
                excel: {'xls': true, 'xlsx': true, 'csv': true},
            }
        },
        methods: {
            getFileSuffix,
        }
    }
</script>

<style scoped lang="scss">
    .file-placeholder {
        width: 136px;
        .file-icon {
            height: 100px;
            margin-bottom: 5px;
            border: 1px dashed #c0ccda;
            box-sizing: border-box;
            border-radius: 6px;
            overflow: hidden;
        }
        .icon {
            height: 98px;
        }
        .p {
            @include ellipsis;
            line-height: normal;
        }
        .file-name {
            font-size: 13px;
            color: #333;
        }
        .file-date {
            font-size: 12px;
            color: #666;
        }
    }

</style>