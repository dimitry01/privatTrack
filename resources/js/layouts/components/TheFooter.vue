<template>
	<footer class="the-footer flex-wrap justify-between" :class="classes">
		<span>COPYRIGHT @ 2020, All rights Reserved</span>
		
	</footer>
</template>

<script>

export default {
	name: "the-footer",
	props: {
		classes: {
			type: String,
		},
	},
}
</script>