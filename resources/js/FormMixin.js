export default {
  data() {
    return {
      fields: {},
      errors: {},
      success: false,
      loaded: true,
      method: 'POST',
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
        axios({
          method: this.method,
          url: this.action,
          data: this.fields
        })
          .then(_ => {
            this.fields = this.fillFields()
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
