function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';

    var decimals = 2

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}
function addZero(data){
    return (data<10) ? "0"+data : data;
}
function generate_token(length){
    //edit the token allowed characters
    var a = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
    var b = [];
    for (var i=0; i<length; i++) {
        var j = (Math.random() * (a.length-1)).toFixed(0);
        b[i] = a[j];
    }
    return b.join("");
}
function getTimestampToDate(timestamp){
    var date = new Date(timestamp*1000);
    var chgTimestamp = date.getFullYear().toString() + '년 '
        +addZero(date.getMonth()+1) + '월 '
        +addZero(date.getDate().toString()) + '일 '
        +addZero(date.getHours().toString()) + '시'
        +addZero(date.getMinutes().toString()) + '분'
    return chgTimestamp;
}
var sprintf = function(str) {
    var args = arguments,
        flag = true,
        i = 1;

    str = str.replace(/%s/g, function() {
        var arg = args[i++];

        if (typeof arg === 'undefined') {
            flag = false;
            return '';
        }
        return arg;
    });
    return flag ? str : '';
};
