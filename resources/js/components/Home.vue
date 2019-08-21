<template>
	<div class="container">
		<p>test</p>
	</div>
</template>
<script>
	import http from '../services/http.js'
	export default {
		data() {
			return {
				name: '',
			}
		},
		computed() {
			console.log('test')
		}
	}
</script>
