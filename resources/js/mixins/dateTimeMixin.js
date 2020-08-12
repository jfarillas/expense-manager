/**
 * Convert Date and Time
 * authored by: Joseph Ian Farillas
 */
export default {
  methods: {
    convertDateTime: (dateTime) => {
      const date = new Date(dateTime);
      return new Date(date.getTime() - (date.getTimezoneOffset() * 60000)).toISOString().slice(0, 19).replace('T', ' ');
    }
  }
};