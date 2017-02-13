<template>
	<div class="entrance-navigation">
		<router-link to="/"  class="entrance-navigation-item" active-class="entrance-navigation-item--active" exact>{{$t('Login')}}</router-link>
		<router-link to="/register" class="entrance-navigation-item" active-class="entrance-navigation-item--active">{{$t('Register')}}</router-link>
	</div>
</template>

<script>
    export default{
        name: "entrance-navigation"
    }
</script>