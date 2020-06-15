/**
 * Object Management
 * authored by: Joseph Ian Farillas
 */
export default {
  methods: {
    reInitialize: (obj) => {
      obj = obj || {};
      _.keys(obj).forEach(index => {
        // Convert it back to original property's data type
        obj[index] = true;
      });
    },

    observerToNormalObj: (obj) => {
      // Let it be a normal object
      return { ...obj };
    },

    strObjToObserver: (obj) => {
      // Convert back to object observer if the permissions object's data type is string
      return (typeof obj === 'string' || obj instanceof String) ? JSON.parse(obj) : obj;
    }
  }
};