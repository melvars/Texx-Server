// Use this hook to manipulate incoming or outgoing data.
// For more information on hooks see: http://docs.feathersjs.com/api/hooks.html

module.exports = function (options = {}) { // eslint-disable-line no-unused-vars
  return async context => {
    const {app, method, result, params} = context;
    const posts = method === 'find' ? result.data : [result];

    await Promise.all(posts.map(async post => {
      post.user = await app.service('users').get(post.userId, params);
    }));

    posts.forEach(post => delete post.userId);

    return context;
  };
};
