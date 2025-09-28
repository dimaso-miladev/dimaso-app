import Vue from 'vue'
import { library, config } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// import { } from '@fortawesome/free-regular-svg-icons'

import {
  faUser, faLock, faSignOutAlt, faCog,
  faEye,
  faEyeDropper,
  faEyeSlash,
  faBell,
  faEnvelope,
  faList,
  faBars,
  faHome,
  faUserCircle,
  faPalette,
  faFont,
  faCubes,
  faTable,
  faChartBar,
  faUsers,
  faBriefcase,
  faSearch
} from '@fortawesome/free-solid-svg-icons'

import {
  faEdge,
  faGithub,
  faGoogle
} from '@fortawesome/free-brands-svg-icons'

config.autoAddCss = false

library.add(
  faUser, faLock, faSignOutAlt, faCog,
  faGithub, faGoogle,
  faEye, faEyeSlash, faEyeDropper,
  faBell, faEnvelope, faList, faBars, faHome, faUserCircle,
  faPalette, faFont, faCubes, faTable, faChartBar,
  faUsers, faBriefcase, faSearch
)

Vue.component('Fa', FontAwesomeIcon)
