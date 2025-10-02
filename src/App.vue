<template>
	<NcAppContent>
		<div id="rsrss">
			<form @submit.prevent="fetchFeed">
				<div class="inputElement">
					<label for="url">URL do feed RSS:</label>
					<input
						id="url"
						v-model="url"
						type="text"
						placeholder="https://..."
						required>
				</div>
				<button type="submit">
					Adicionar
				</button>
			</form>
			<div v-if="feeds">
				<div
					v-for="{ origin, item } in feeds"
					id="item"
					:key="item.guid">
					<div class="itemHeader">
						<h2>{{ item.title }}</h2>
						<NcActions>
							<NcActionLink :href="item.link" class="redirectButton">
								<template #icon>
									<OpenInNew :size="20" />
								</template>
								Ir para a publicação
							</NcActionLink>
						</NcActions>
					</div>
					<div class="itemDetails">
						<NcChip :text="item.category" no-close variant="primary" />
						<div>
							<span class="caption">{{ origin }}</span>
							<span class="caption">{{ new Date(item.pubDate).toLocaleString() }}</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</NcAppContent>
</template>

<script>
import NcAppContent from '@nextcloud/vue/dist/Components/NcAppContent.js'
import NcActions from '@nextcloud/vue/components/NcActions'
import NcActionLink from '@nextcloud/vue/components/NcActionLink'
import NcChip from '@nextcloud/vue/components/NcChip'

import OpenInNew from 'vue-material-design-icons/OpenInNew.vue'

import axios from '@nextcloud/axios'
import { generateOcsUrl } from '@nextcloud/router'
import { loadState } from '@nextcloud/initial-state'

export default {
	name: 'App',
	components: {
		NcAppContent,
		NcActions,
		NcActionLink,
		NcChip,
		OpenInNew,
	},
	data() {
		return {
			url: '',
			feeds: loadState('rsrss', 'feeds'),
		}
	},
	methods: {
		async fetchFeed() {
			const { data } = await axios.post(generateOcsUrl('apps/rsrss/api'), {
				url: this.url,
			})

			this.feeds = data.ocs.data.feed
		},
	},
}
</script>

<style scoped lang="scss">
#rsrss {
	display: flex;
	align-items: center;
	margin: 16px auto;
	flex-direction: column;
	max-width: 50%;
	form {
		display: flex;
		align-items: end;
		input {
			width: 400px;
			height: 20px;
		}
		button {
			height: 20px;
		}
	}
}

.inputElement {
	display: flex;
	flex-direction: column;
}

.itemDetails {
	display: flex;
	justify-content: space-between;
}

.caption {
	font-size: 10pt;
	color: gray;
	display: block;
	text-align: right;
}

#item + #item {
	margin-top: 16px;
	border-top: 1px solid gray;
}

.itemHeader {
	display: flex;
	justify-content: space-between;
	align-items: baseline;
}

.redirectButton {
	margin-left: 16px;
	height: 100%;
}
</style>
