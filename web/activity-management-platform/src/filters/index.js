import {
    formatTimeNumber
} from '@/utils';
import {
    UPLOAD_DOMAIN
} from '@/api/config.js';

export default {
    formatDatetime(datetime) {
        if (!datetime) {
            return '';
        }
        datetime = datetime.replace(/-/g, '/');
        datetime = datetime.replace('T', ' ');
        datetime = datetime.replace('Z', '');
        if (parseInt(datetime.indexOf('+')) > -1) {
            datetime = datetime.substring(0, datetime.indexOf('+'));
        }
        if (parseInt(datetime.indexOf('.')) > -1) {
            datetime = datetime.substring(0, datetime.indexOf('.'));
        }
        const dt = datetime.split(' ');
        const date = dt[0] ? dt[0].split('/') : [];
        const time = dt[1] ? dt[1].split(':') : [];
        const year = parseInt(date[0]);
        const month = parseInt(date[1]);
        const day = parseInt(date[2]);
        const hour = parseInt(time[0]) || 0;
        const minute = parseInt(time[1]) || 0;
        const second = parseInt(time[2]) || 0;
        let result = `${year}/${formatTimeNumber (month)}/${formatTimeNumber (day)} ${[
      hour,
      minute,
      second,
    ]
      .map (formatTimeNumber)
      .join (':')}`;
        if (
            isNaN(year) ||
            isNaN(month) ||
            isNaN(day) ||
            isNaN(hour) ||
            isNaN(minute) ||
            isNaN(second)
        ) {
            result = '';
        }
        return result;
    },
    date(nS) {
        if (!nS) {
            return '';
        }
        const now = new Date(parseInt(nS) * 1000);
        const year = now.getFullYear();
        const month = now.getMonth() + 1;
        const date = now.getDate();
        return [year, month, date].map(formatTimeNumber).join('-');
    },
    dateMonth(nS) {
        if (!nS) {
            return '';
        }
        const now = new Date(parseInt(nS) * 1000);
        const month = now.getMonth() + 1;
        const date = now.getDate();
        return [month, date].map(formatTimeNumber).join('-');
    },
    time(nS) {
        if (!nS) {
            return '';
        }
        const now = new Date(parseInt(nS) * 1000);
        const hour = now.getHours();
        const minute = now.getMinutes();
        const second = now.getSeconds();
        return [hour, minute, second].map(formatTimeNumber).join(':');
    },
    datetime(nS) {
        if (!nS) {
            return '';
        }
        const now = new Date(parseInt(nS) * 1000);
        const year = now.getFullYear();
        const month = now.getMonth() + 1;
        const date = now.getDate();
        const hour = now.getHours();
        const minute = now.getMinutes();
        const second = now.getSeconds();
        return `${year}-${[month, date]
      .map (formatTimeNumber)
      .join ('-')} ${[hour, minute, second].map (formatTimeNumber).join (':')}`;
    },
    formatUploadUrl(url) {
        if (url) {
            let re = new RegExp('^(http|https)://.*$');
            if (re.test(url)) {
                return url;
            } else {
                return UPLOAD_DOMAIN + url;
            }
        } else {
            return url;
        }
    },
    formatImage(url, width, height) {
        if (url) {
            let re = new RegExp('^(http|https)://.*$');
            if (re.test(url)) {
                return url;
            } else {
                if (width && height) {
                    const imgName = url.substring(0, url.lastIndexOf('.'));
                    const imgSuffix = url.substring(url.lastIndexOf('.'));
                    return imgName + '_' + width + 'x' + height + imgSuffix;
                } else {
                    return url;
                }
            }
        } else {
            return url;
        }
    },
    formatApprovalStatus(value) {
        const text = ['', '待审批', '审批通过', '审批驳回'];
        return text[value];
    },
};
