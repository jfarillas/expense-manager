/**
 * Customize Auth setup
 * authored by: Joseph Ian Farillas
 */
export default {
  methods: {
    $is(roleName) {
      return Auth.indexOf(roleName) !== -1;
    },
    $id() {
      return AuthId;
    }
  }
}