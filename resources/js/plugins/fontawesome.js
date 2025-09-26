import Vue from 'vue'
import { library, config } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// import { } from '@fortawesome/free-regular-svg-icons'

import {
  faUser, faLock, faSignOutAlt, faCog,
  faEye,
  faEyeDropper,
  faEyeSlash
} from '@fortawesome/free-solid-svg-icons'

import {
  faEdge,
  faGithub,
  faGoogle
} from '@fortawesome/free-brands-svg-icons'

config.autoAddCss = false

library.add(
  faUser, faLock, faSignOutAlt, faCog, faGithub,faGoogle, faEye, faEyeSlash
)

Vue.component('Fa', FontAwesomeIcon)
