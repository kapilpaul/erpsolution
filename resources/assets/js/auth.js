export default function(Vue) {
  let authenticatedUser = {};

  Vue.auth = {
    setToken(token, expiration) {
      localStorage.setItem("token", token);
      localStorage.setItem("expiration", expiration);
    },

    getToken() {
      var token = localStorage.getItem("token");
      var expiration = localStorage.getItem("expiration");

      if (!token || !expiration) return null;

      if (Date.now() > parseInt(expiration)) {
        this.destroyToken();
        return null;
      } else {
        return token;
      }
    },

    destroyToken() {
      localStorage.removeItem("token");
      localStorage.removeItem("expiration");
    },

    isAuthenticated() {
      if (this.getToken()) return true;
      else return false;
    },

    setAuthenticateduser(data) {
      authenticatedUser = data;
    },

    getAuthenticateduser() {
      return authenticatedUser;
    },

    getHeader() {
      const tokenData = this.getToken();
      return {
        headers: {
          Accept: "application/json",
          Authorization: "Bearer " + tokenData
        }
      };
    }
  };

  Object.defineProperties(Vue.prototype, {
    $auth: {
      get() {
        return Vue.auth;
      }
    }
  });
}
