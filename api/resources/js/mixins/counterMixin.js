/**
 * Counters - Dynamic usage
 * authored by: Joseph Ian Farillas
 */
export default {
  methods: {
    counter: (collection) => { 
      collection = collection || {};
      let wrapped = [];
      _.keys(collection).forEach(index => {
        _(wrapped)
        .push(index)
        .value();
      });
      return wrapped;
    }
  }
}