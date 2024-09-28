import {createApp} from "vue"
import {vuetify} from"../util/vuetify"
import Credit from "./Dbg.vue"

(window.onload = () => {

  createApp(Credit).use(vuetify).mount('#container')

});