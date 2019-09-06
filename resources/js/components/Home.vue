<template>
	<div>
		<div class="container">
			<h1 class="green-text">for "world of 338"</h1>
			<div>
				<button class="btn teal waves-effect waves-light" @click="update()">
					<i class="material-icons left">update</i>更新
				</button>
			</div>
			<!--<div id="mapWrapper">
				<canvas id="map" width="1000" height="1000"></canvas>
			</div>-->
			<h2 class="blue-text">マーカー追加</h2>
			<div class="row">
				<div class="col s12 input-field">
					<input type="text" v-model="name" id="form_name" required>
					<label for="form_x">名前</label>
				</div>
				<div class="col s12 m4 input-field">
					<input type="number" v-model="x" id="form_x" required>
					<label for="form_x">x座標</label>
				</div>
				<div class="col s12 m4 input-field">
					<input type="number" v-model="y" id="form_y" required>
					<label for="form_x">y座標</label>
				</div>
				<div class="col s12 m4 input-field">
					<input type="number" v-model="z" id="form_z" required>
					<label for="form_x">z座標</label>
				</div>
				<div class="col s12 input-fileld">
					<button class="btn teal waves-effect waves-light" @click="createMarker()">
						<i class="material-icons left">update</i>追加
					</button>
				</div>
			</div>
			<h2 class="blue-text">マーカー一覧</h2>
			<div class="switch">
				<label>
					OverWorld
					<input type="checkbox" v-model="isNether">
					<span class="lever"></span>
					Nether
				</label>
			</div>
			<table class="stripe">
				<tr>
					<th>名前</th>
					<th v-show="isNether">ネザー座標</th>
					<th v-show="!(isNether)">地上座標</th>
					<th></th>
				</tr>
				<tr v-for="marker of markers" :key="marker.id">
					<td>{{ marker.name }}</td>
					<td v-show="!(isNether)">({{ marker.x }}, {{ marker.y }}, {{ marker.z }})</td>
					<td v-show="isNether">({{ marker.nether_x }}, {{ marker.nether_y }}, {{ marker.nether_z }})</td>
					<td><a href="#modal_delete" class="red-text modal-trigger" @click="updateDeleteId(marker.id)">削除</a></td>
				</tr>
			</table>
		</div>
		<div id="modal_delete" class="modal">
			<div class="modal-content">
				<h4 class="red-text">マーカー削除</h4>
				<p>この操作は取り消せません。本当に削除しますか。</p>
			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-close waves-effect waves-grey btn-flat">キャンセル</a>
				<a href="#!" class="modal-close waves-effect waves-red btn-flat red-text" @click="deleteMarker()">削除する</a>
			</div>
		</div>
	</div>
</template>
<style scoped>
[id="mapWrapper"] {
	width: 100%;
	height: 60vh;
	overflow: auto;
}
[id="map"] {
	background: white;
}
.switch label input[type=checkbox]:checked+.lever:after {
	background-color: #b71c1c !important;
}
.switch label input[type=checkbox]:checked+.lever {
	background-color: #ffcdd2 !important;
}
</style>
<script>
	import http from '../services/http.js'
	export default {
		data() {
			return {
				markers: [],
				name: '',
				x: null,
				y: null,
				z: null,
				delete_id: null,
				isNether: false,
			}
		},
		mounted() {
			// マーカー情報取得
			this.update()
			// モーダルinit
			let elems = document.querySelectorAll('.modal')
			M.Modal.init(elems, {})
			// mapスクロール
		},
		computed: {
			x_list() { return this.markers.map(marker => marker.x) },
			z_list() { return this.markers.map(marker => marker.z) },
			max_x() {
				let max_x = this.x_list.reduce((x, cur) => Math.max(x, cur), -Infinity)
				return max_x + 200
			},
			min_x() {
				let min_x = this.x_list.reduce((x, cur) => Math.min(x, cur), Infinity)
				return min_x - 100
			},
			max_z() {
				let max_z = this.z_list.reduce((z, cur) => Math.max(z, cur), -Infinity)
				return max_z + 100
			},
			min_z() {
				let min_z = this.z_list.reduce((z, cur) => Math.min(z, cur), Infinity)
				return min_z - 100
			},
			canvas_width() { return this.max_x - this.min_x },
			canvas_height() { return this.max_z - this.min_z }
		},
		methods: {
			update() {
				http.get('marker/list', {}, res => {
					this.markers = res.data
					this.markers.forEach(marker => {
						marker.x = Number(marker.x)
						marker.y = Number(marker.y)
						marker.z = Number(marker.z)
						marker.nether_x = Math.round(marker.x / 8)
						marker.nether_y = marker.y
						marker.nether_z = Math.round(marker.z / 8)
					})
					// マーカー描画
					// this.drawMap()
					this.toastMessage('マーカー情報を取得しました')
				}, err => {
					this.toastError('マーカー情報の取得に失敗しました')
				})
			},
			createMarker() {
				let payload = {
					name: this.name,
					x: this.x,
					y: this.y,
					z: this.z
				}
				http.post('marker/create', payload, res => {
					payload.id = res.data.id

					payload.nether_x = Math.round(payload.x / 8)
					payload.nether_y = payload.y
					payload.nether_z = Math.round(payload.z / 8)

					this.markers.push(payload)
					// this.clear()
					// this.drawMap()
					this.toastMessage('マーカーを追加しました')
				}, err => {
					this.toastError('マーカーの追加に失敗しました')
				})
			},
			deleteMarker() {
				let payload = {
					id: this.delete_id
				}
				http.post('marker/delete', payload, res => {
					this.markers = this.markers.filter(marker => {
						if (marker.id != this.delete_id) return true
					})
					// this.drawMap()
					this.toastMessage('マーカーを削除しました')
				}, err => {
					this.toastError('マーカーの削除に失敗しました')
				})
			},
			updateDeleteId(id) {
				this.delete_id = id
			},
			toastMessage(message) {
				M.toast({html: message, classes: 'teal white-text'})
			},
			toastError(message) {
				M.toast({html: message, classes: 'red white-text'})
			},
			clear() {
				this.name = ''
				this.x = null
				this.y = null
				this.z = null
			},
			drawMap() {
				this.resetMap()
				this.markers.forEach(this.plotMarker)
			},
			resetMap() {
				let canvas = document.getElementById('map')
				let ctx = canvas.getContext('2d')
				canvas.setAttribute('width', this.canvas_width)
				canvas.setAttribute('height', this.canvas_height)
				ctx.clearRect(0, 0, canvas.width, canvas.height)
			},
			plotMarker(marker) {
				let canvas = document.getElementById('map')
				let ctx = canvas.getContext('2d')
				let x = marker.x - this.min_x
				let z = marker.z - this.min_z

				ctx.beginPath()
				ctx.arc(x, z, 10, 0, Math.PI * 2, true)
				ctx.fillStyle = 'hsl(' + Math.random() * 360 + ', 80%, 60%)'
				ctx.fill()
				ctx.fillStyle = 'black'
				ctx.font = "20px Noto Sans JP"
				ctx.fillText(marker.name, x, z)
				ctx.fillText(`(${marker.x},${marker.y},${marker.z})`, x, z + 20)
			}
		}
	}
</script>
