export const setCookie = function (key, value) {
  const expires = new Date();
  expires.setMonth(expires.getMonth() + 1);
  document.cookie =
    key +
    "=" +
    value +
    ";expires=" +
    expires.toUTCString() +
    ";SameSite=Strict";
};

export const getCookie = function (name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(";").shift();
};

export const removeCookie = function (name) {
  const expires = new Date();
  expires.setDate(expires.getDate() - 1);
  document.cookie = name + "=;expires=" + expires.toUTCString();
};
