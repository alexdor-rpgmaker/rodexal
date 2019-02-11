export default {
  data() {
    return {
      fields: {},
      errors: {},
      success: false,
      loaded: true,
      action: '',
      redirection: ''
    }
  },
  methods: {
    submit() {
      if (this.loaded) {
        this.loaded = false
        this.success = false
        this.errors = {}
        axios
          .post(this.action, this.fields)
          .then(_ => {
            this.fields = {}
            this.loaded = true
            this.success = true
            if (this.redirection !== '') {
              window.location.replace(this.redirection)
            }
          })
          .catch(error => {
            this.loaded = true
            if (error.response.status === 422) {
              this.errors = error.response.data.errors || {}
            }
          })
      }
    }
  }
}
