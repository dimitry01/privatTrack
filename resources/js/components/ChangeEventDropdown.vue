<template>
	<vs-dropdown vs-trigger-click>
		<small class="flex cursor-pointer">By {{ selected }}<feather-icon icon='ChevronDownIcon' svgClasses='h-4 w-4' class='ml-1'></feather-icon></small>
		<vs-dropdown-menu class="w-32">
			<vs-dropdown-item @click="$emit('eventChanged', event, 'opens')">By Opens</vs-dropdown-item>
			<vs-dropdown-item @click="$emit('eventChanged', event, 'clicks')">By Clicks</vs-dropdown-item>
		</vs-dropdown-menu>
	</vs-dropdown>
</template>

<script>
export default {
	props: ['event','selected'],
}
</script>
