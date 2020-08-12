/**
 * Accessing/disabling fields
 * authored by: Joseph Ian Farillas
 */
import Permissions from './permissions'

export default {
  methods: {
    ariaNotEditable: (state) => { return (state) ? true : false },

    checkboxValProcess: (obj, collection, action) => {
      // Check if the permission/s has/have been selected, update the permissions data
      _.keys(collection).forEach(index => {
        // Replace with selected permission/s
        action.$set(obj, collection[index], collection[index]);
      });

      return obj;
      
    }
  }
}