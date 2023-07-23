import {createApp} from "vue"
import {vuetify} from"../util/vuetify"
import Credit from "./Credit.vue"

(window.onload = () => {

  createApp(Credit).use(vuetify).mount('#container')

});