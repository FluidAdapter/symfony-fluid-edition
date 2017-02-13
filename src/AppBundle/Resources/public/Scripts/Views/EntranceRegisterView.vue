<template>
	<div class="entrance">
		<entrance-header></entrance-header>
		<entrance-navigation></entrance-navigation>

		<p class="entrance-text">
			Bitte geben Sie Ihre E-mail an, alles weitere wird im nächsten Schritt abgefragt.
		</p>

		<div class="entrance-field">
			<input type="email" id="email" v-model="email" class="entrance-input" placeholder="E-Mail"/>
		</div>

		<button class="entrance-loginButton">Register</button>
	</div>
</template>

<script>
    export default{
        name: "entrance-register-view"
    }
</script>