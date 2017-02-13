<template>
	<div class="entrance">
		<entrance-header></entrance-header>
		<entrance-navigation></entrance-navigation>

		<form @submit.prevent="submit">
			<p class="entrance-text" v-html="$t('Please enter your email address. We will send you an email to reset your password.')">
			</p>

			<div class="entrance-field">
				<input name="email"
							 v-model="email"
							 v-validate:email.initial="'required|email'"
							 class="entrance-input"
							 :class="{'entrance-field--invalid': errors.has('email') }"
							 type="text"
							 placeholder="E-Mail" >
				<div v-show="errors.has('email')" class="entrance-field-error">{{ $t(errors.first('email')) }}</div>
			</div>

			<button class="entrance-loginButton" @click="submit">{{$t("Send E-Mail")}}</button>
		</form>
	</div>
</template>

<script>
    export default{
        name: "entrance-forgot-password-view",
        data: function () {
            return {
                email: '',
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
                        email: this.email
                    };
                    this.error = '';
                    this.$http.post(entrance.baseUrl + 'api/reset-password', params).then(response => {
                    }, response => {
                        this.error = response.body.message;
                    });
                });
            }
        }
    }
</script>