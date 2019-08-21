<template>
	<div>
		<div class="container">
			<h1 class="green-text">for "world of 338"</h1>
			<div>
				<button class="btn teal waves-effect waves-light" @click="update()">
					<i class="material-icons left">update</i>更新
				</button>
			</div>
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
					<label for="form_x">y座標(省略可)</label>
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
			<table class="stripe">
				<tr>
					<th>名前</th>
					<th>座標</th>
					<th></th>
				</tr>
				<tr v-for="marker of markers" :key="marker.id">
					<td>{{ marker.name }}</td>
					<td>({{ marker.x }}, {{ marker.y }}, {{ marker.z }})</td>
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
			}
		},
		mounted() {
			// マーカー情報取得
			http.get('marker/list', {}, res => {
				this.markers = res.data
			}, err => {
				this.toastError('マーカー情報の取得に失敗しました')
			})
			// モーダルinit
			let elems = document.querySelectorAll('.modal');
 			M.Modal.init(elems, {});
		},
		methods: {
			update() {
				http.get('marker/list', {}, res => {
					this.markers = res.data
					this.toastMessage('マーカー情報を更新しました')
				}, err => {
					this.toastError('マーカー情報の更新に失敗しました')
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
					this.markers.push(payload)
					this.clear()
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
						marker.id != this.delete_id
					})
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
			}
		}
	}
</script>
