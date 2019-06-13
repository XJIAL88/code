<template>
    <div class="form-datetime-range">
        <el-date-picker
            v-model="datetimeRange"
            value-format="timestamp"
            format="yyyy-MM-dd HH:mm"
            type="datetimerange"
            range-separator="至"
            start-placeholder="开始时间"
            end-placeholder="结束时间"
            :default-time="['00:00:00', '23:59:59']"
            :clearable="clearable"
            :editable="editable"
            @change="onChange"
        ></el-date-picker>
    </div>
</template>
<script>
export default {
    name: "FormDatetimeRange",
    props: {
        startAt: {
            type: [Number, String],
            default: 0
        },
        endAt: {
            type: [Number, String],
            default: 0
        },
        //
        clearable: {
            type: Boolean,
            default: true
        },
        editable: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            datetimeRange: []
        };
    },
    watch: {
        startAt(val) {
            if (val) {
                this.$set(this.datetimeRange, 0, val * 1000);
            } else {
                this.datetimeRange = [];
            }
        },
        endAt(val) {
            if (val) {
                this.$set(this.datetimeRange, 1, val * 1000);
            } else {
                this.datetimeRange = [];
            }
        },
        datetimeRange(val) {
            if (val) {
                this.$emit("update:startAt", val[0] / 1000);
                this.$emit("update:endAt", val[1] / 1000);
            } else {
                this.$emit("update:startAt", "");
                this.$emit("update:endAt", "");
            }
        }
    },
    methods: {
        onChange(val) {
            this.datetimeRange = val;
        }
    },
    mounted() {
        if (this.startAt && this.endAt) {
            this.datetimeRange = [this.startAt * 1000, this.endAt * 1000];
        }
    }
};
</script>

