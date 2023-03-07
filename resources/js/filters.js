import Vue from 'vue'

Vue.filter('capFirstLetter', (str) =>
{
    if (str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
});

Vue.filter('truncate', (string, value) => {
    if (string && string.length > value) {
        return string.substring(0, value) + "...";
    } else {
        return string;
    }
});

Vue.filter('pluralize', (int, string) => {
    if(parseInt(int) < 2){
        return int + ' ' + string
    }else{
        return int + ' '+ string +'s'
    }
})
