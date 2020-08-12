/**
 * Router Management
 * authored by: Joseph Ian Farillas
 */
export default {
  methods: {
    navigationDuplicate: (error) => {
      if (error.name !== "NavigationDuplicated") {
        throw error;
      }
    }
  }
};