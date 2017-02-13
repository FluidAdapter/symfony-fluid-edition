<template>
	<div class="entrance">
		<entrance-header></entrance-header>
		<entrance-navigation></entrance-navigation>

		<form @submit.prevent="submit">
			<div class="entrance-field">
				<input name="username"
							 v-model="username"
							 v-validate:username.initial="'required'"
							 class="entrance-input"
							 :class="{'entrance-field--invalid': errors.has('username') }"
							 type="text"
							 placeholder="Username" >
				<div v-show="errors.has('username')" class="entrance-field-error">{{ $t(errors.first('username')) }}</div>
			</div>

			<div class="entrance-field">
				<input name="password"
							 v-model="password"
							 v-validate:password.initial="'required'"
							 class="entrance-input"
							 :class="{'entrance-field--invalid': errors.has('password') }"
							 type="password"
							 placeholder="Password" >
				<div v-show="errors.has('password')" class="entrance-field-error">{{ $t(errors.first('password')) }}</div>
			</div>

			<p class="entrance-error" v-if="error" v-html="$t(error)"></p>

			<router-link to="/forgot-password" class="entrance-forgotPassword">{{$t("Don't remember your password?")}}</router-link>

			<button class="entrance-loginButton" @click="submit">Login</button>
		</form>
	</div>
</template>

<script>
    export default{
        name: "entrance-index-view",
        data: function () {
            return {
                username: '',
                password: '',
                error: ''
            }
        },
        methods: {
            submit: function (e) {
                e.preventDefault();
                this.$validator.validateAll().then(success => {
                    if (! success) {
                        // handle error
                        return;
                    }
                    var params = {
                        username: this.username,
                        password: this.password
                    };
                    this.error = '';
                    this.$http.post(entrance.baseUrl + 'api/login', params).then(response => {
                        console.log(response.body);
                    }, response => {
                        this.error = response.body.message;
                    });
                });
            }
        }
    }
</script>
