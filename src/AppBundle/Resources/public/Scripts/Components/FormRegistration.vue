<template>
    <form method="post" class="formRegistration" v-on:submit.prevent="onSubmit">
        <div  class="formRegistration-field">
            <label class="formRegistration-label" for="username">Username</label>
            <input type="text" class="formRegistration-input" v-model="username" />
        </div>

        <div  class="formRegistration-field">
            <label class="formRegistration-label" for="email">E-Mail</label>
            <input type="text" class="formRegistration-input" v-model="email" />
        </div>

        <div  class="formRegistration-field">
            <label class="formRegistration-label" for="password">Password:</label>
            <input type="password" class="formRegistration-input" v-model="password" />
        </div>

        <br/>
        <button type="submit" class="formRegistration-submit">Login</button>
    </form>
</template>

<script>
export default {
  name: 'formRegistration',
  props: ['action'],
  data: function() {
	return {
  		username: null,
  		password: null,
  		email: null
  	}
  },
  methods: {
  	increment: function() {
  		this.value++;
  	},
  	onSubmit: function() {
  	    console.log(this.username);
        this.$http.post(this.action, {
        }).then(function(result) {
            console.log(result);
            //... success
        }, function(){
            //... fail
        });
  	}
  }
}
</script>