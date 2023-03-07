import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const opts = {
    theme: {
        themes: {
            light: {
                primary: '#004EFF',
                primary_variant: '#3371FF',
                secondary: '#FFB100',
                light_text: '#3e3e3e',
                dark_text: '#1c1c1c',
                grey_text: '#19191a',
                background: '#f9f9f9'
            }
        }
    }
}

export default new Vuetify(opts)
