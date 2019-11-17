let colors = {
	primary: '#7367F0',
	success: '#28C76F',
	danger: '#EA5455',
	warning: '#FF9F43',
	dark: '#1E1E1E',
}
import {getLocalUser} from '../auth';
const user = getLocalUser();

const state = {
	isSidebarActive: true,
	breakpoint: null,
	sidebarWidth: "default",
	reduceButton: false,
	bodyOverlay: false,
	sidebarItemsMin: false,
	theme: 'light',
	themePrimaryColor: colors.primary,
	uploadPercent: null,
	exportPercent: null,
	campaigns: [],
	countries: [],
	files: [],
	visitors: [],
	currentUser: user,
	isLoggedIn: !!user,
	loading: false,
	auth_error: null,
	settings: {
		token: null,
	}
}

export default state