// function toBoolean(value: string | boolean | number | undefined) {
//   if (value === undefined) {
//     return false;
//   }

//   const truthy = [true, 'true', 1, '1'];
//   return truthy.includes(value);
// }

export default {
  appName: process.env.MIX_APP_NAME,
  appEnv: process.env.MIX_APP_ENV,
  appUrl: process.env.MIX_APP_URL,
  alefUrl: process.env.MIX_ALEF_URL,

  basePath: process.env.MIX_BASE_PATH,
  baseApi: process.env.MIX_BASE_API,
};
