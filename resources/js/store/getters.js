const getters = {
  uploadPercent: state => state.uploadPercent,
  exportPercent: state => state.exportPercent,
  campaigns: state => state.campaigns,
  countries: state => state.countries,
  files: state => state.files,
  visitors: state => state.visitors,
  isLoggedIn: state => state.isLoggedIn,
  currentUser: state => state.currentUser,
  authError: state => state.auth_error,
  token: state => state.settings.token,
}

export default getters