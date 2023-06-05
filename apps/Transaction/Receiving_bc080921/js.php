<script>
	//alert("jooosss");
	cm = $('#nocmt').val();
	co = $('#nocolor').val();
	ins = $('#noinseams').val();
	siz = $('#nosizes').val();
	trimcmt = cm.trim();
	expcmt = trimcmt.split('/');
	cmts = expcmt.join('-');

	trimcolor = co.trim();
	expcolor = trimcolor.split('#');
	if (expcolor.length > 1) {
		expcolor1 = expcolor.join('K-');
	} else {
		expcolor1 = trimcolor;
	}
	expcolor2 = expcolor1.split(' ');
	if (expcolor2.length > 1) {
		expcolor3 = expcolor2.join('_');
	} else {
		expcolor3 = expcolor1;
	}
	expcolor4 = expcolor3.split('/');
	if (expcolor4.length > 1) {
		color = expcolor4.join('G-');
	} else {
		color = expcolor3;
	}

	(function($) {

		'use strict';

		var datatableInit = function() {
			var $table = $('#datatable-ajax2');
			$table.dataTable({
				"processing": true,
				"serverSide": true,
				"ajax": "apps/Transaction/Receiving/data.php?cm=" + cmts + "&co=" + color + "&in=" + ins + "&si=" + siz,
				"fnRowCallback": function(nRow, aData, iDisplayIndex) {
					var index = iDisplayIndex + 1;
					$('td:eq(0)', nRow).html(index);
					return nRow;
				},
				"lengthMenu": [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, "All"]
				]
			});

		};

		$(function() {
			datatableInit();
		});

	}).apply(this, [jQuery]);



	function pindahData2(cmt, colors, inseams, sizes, saw) {

		//CMT
		if (cmt == '') {
			swal({
				icon: 'error',
				title: 'CMT Not Found',
				text: 'Please Fill CMT Number and Colors',
				footer: '<a href>Why do I have this issue?</a>'
			});
		} else if (colors == '') {
			swal({
				icon: 'error',
				title: 'Colors Not Found',
				text: 'Please Fill CMT Number and Colors',
				footer: '<a href>Why do I have this issue?</a>'
			});
		} else {
			trimcmt = cmt.trim();
			expcmt = trimcmt.split('/');
			cmts = expcmt.join('-');
			//Color
			trimcolor = colors.trim();
			expcolor = trimcolor.split('#');
			if (expcolor.length > 1) {
				expcolor1 = expcolor.join('K-');
			} else {
				expcolor1 = trimcolor;
			}
			expcolor2 = expcolor1.split(' ');
			if (expcolor2.length > 1) {
				expcolor3 = expcolor2.join('_');
			} else {
				expcolor3 = expcolor1;
			}
			expcolor4 = expcolor3.split('/');
			if (expcolor4.length > 1) {
				color = expcolor4.join('G-');
			} else {
				color = expcolor3;
			}

			var hal = $('#getp').val();
			// $.ajax({
			// 			type: 'POST',
			// 			url:  "apps/Transaction/Receiving/sourcedatasewing.php?p="+hal+"&cm="+cmts+"&co="+color+"&in="+inseams+"&si="+sizes+"&saw="+saw,
			// 			success: function() {
			// 				$('#loader-on').click();

			// 				$('#tampilsewing').load("apps/Transaction/Receiving/sourcedatasewing.php?p="+hal+"&cm="+cmts+"&co="+color+"&in="+inseams+"&si="+sizes+"&saw="+saw);

			// 				$('#loader-off').click();
			// 			}
			// 		});

			window.location = "content.php?p=" + hal + "&cm=" + cmts + "&co=" + color + "&in=" + inseams + "&si=" + sizes + "&saw=" + saw;
		}
	}

	function viewdata(a) {
		$('#view').val(a);
		javascript: window.location.href = "content.php?p=" + $('#getp').val() + "_" + a;
	}

	function conf(cmt, colors, exdate) {
		if ($('#nocmt').val() == '' || $('#nocolor').val() == '' || $('#exftydate').val() == '' || $('#noinseams').val() == '' || $('#nosizes').val() == '' || $('#qty_in').val() == '') {
			swal({
				icon: 'error',
				title: 'Data Not Complete',
				text: 'Check Data cmt,colors,ex_fty_date,inseams,sizes,qty',
				timer: 2000,
			});
			$('#nocmt').val('');
			$('#nocolor').val('');
			$('#exftydate').val('');
			$('#exftydateasli').val('');
			$('#noinseams').val('');
			$('#nosizes').val('');
			$('#qty_in').val('');
		} else if ($('#wo_no').val() != '') {

			if (cmt != $('#wo_no').val() && colors != $('#garment_colors').val() && $('#exftydate').val() != $('#ex_fty_date').val()) {
				swal({
					icon: 'error',
					title: 'CMT Colors Ex Fty Date Not Same',
					text: 'CMT Colors Ex Fty Date must same',
					timer: 2000,
				});
				$('#nocmt').val('');
				$('#nocolor').val('');
				$('#exftydate').val('');
				$('#exftydateasli').val('');
				$('#noinseams').val('');
				$('#nosizes').val('');
				$('#qty_in').val('');
			} else if (cmt == $('#wo_no').val() && colors != $('#garment_colors').val() && $('#exftydate').val() != $('#ex_fty_date').val()) {
				swal({
					icon: 'error',
					title: 'CMT Colors Ex Fty Date Not Same',
					text: 'CMT Colors Ex Fty Date must same',
					timer: 2000,
				});
				$('#nocmt').val('');
				$('#nocolor').val('');
				$('#exftydate').val('');
				$('#exftydateasli').val('');
				$('#noinseams').val('');
				$('#nosizes').val('');
				$('#qty_in').val('');
			} else if (cmt != $('#wo_no').val() && colors == $('#garment_colors').val() && $('#exftydate').val() == $('#ex_fty_date').val()) {
				swal({
					icon: 'error',
					title: 'CMT Colors Ex Fty Date Not Same',
					text: 'CMT Colors Ex Fty Date must same',
					timer: 2000,
				});
				$('#nocmt').val('');
				$('#nocolor').val('');
				$('#exftydate').val('');
				$('#exftydateasli').val('');
				$('#noinseams').val('');
				$('#nosizes').val('');
				$('#qty_in').val('');
			} else if (cmt == $('#wo_no').val() && colors != $('#garment_colors').val() && $('#exftydate').val() == $('#ex_fty_date').val()) {
				swal({
					icon: 'error',
					title: 'CMT Colors Ex Fty Date Not Same',
					text: 'CMT Colors Ex Fty Date must same',
					timer: 2000,
				});
				$('#nocmt').val('');
				$('#nocolor').val('');
				$('#exftydate').val('');
				$('#exftydateasli').val('');
				$('#noinseams').val('');
				$('#nosizes').val('');
				$('#qty_in').val('');
			} else if (cmt == $('#wo_no').val() && colors == $('#garment_colors').val() && $('#exftydate').val() != $('#ex_fty_date').val()) {
				swal({
					icon: 'error',
					title: 'CMT Colors Ex Fty Date Not Same',
					text: 'CMT Colors Ex Fty Date must same',
					timer: 2000,
				});
				$('#nocmt').val('');
				$('#nocolor').val('');
				$('#exftydate').val('');
				$('#exftydateasli').val('');
				$('#noinseams').val('');
				$('#nosizes').val('');
				$('#qty_in').val('');
			} else {
				$('#conf').val(9);
				swal({
						title: "Are You Sure?",
						content: content,
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							swal("Saved!", {
								icon: "success",
							});
							color = $('#no_colors').val();
							cmts = $('#no_cmt').val();
							inseams = $('#no_inseams').val();
							sizes = $('#no_sizes').val();
							exftydate = $('#exftydate').val();
							saw = $('#saw').val();
							var data = $('.form-user').serialize();
							$.ajax({
								type: 'POST',
								url: "apps/Transaction/Receiving/simpan.php",
								data: data,
								success: function() {
									$('#loading-on').click();
									//$('#datatable-ajax').DataTable().ajax.reload();;
									$('#tampilsewing').load("apps/Transaction/Receiving/sourcedatasewing.php");
									$('#cekwip').load("apps/Transaction/Receiving/cekwip.php?cm=" + cmts + "&co=" + color + "&in=" + inseams + "&si=" + sizes + "&ex=" + exftydate + "&saw=" + saw);
									$('#cart').load("apps/Transaction/Receiving/cart.php");
									$('#conf').val('');
									$('#loading-off2').click();
									$('#nocmt').val('');
									$('#nocolor').val('');
									$('#noinseams').val('');
									$('#nosizes').val('');
									$('#exftydate').val('');
									$('#exftydateasli').val('');
									$('#qty_in').val('');

								}
							});
						}
					});
			}
		} else {
			$('#conf').val(9);
			swal({
					title: "Are You Sure?",
					content: content,
					icon: "warning",
					buttons: true,
					dangerMode: true,
				})
				.then((willDelete) => {
					if (willDelete) {
						swal("Saved!", {
							icon: "success",
						});
						color = $('#no_colors').val();
						cmts = $('#no_cmt').val();
						exftydate = $('#exftydate').val();
						inseams = $('#no_inseams').val();
						sizes = $('#no_sizes').val();
						saw = $('#saw').val();
						var data = $('.form-user').serialize();
						$.ajax({
							type: 'POST',
							url: "apps/Transaction/Receiving/simpan.php",
							data: data,
							success: function() {
								$('#loading-on').click();
								//$('#datatable-ajax').DataTable().ajax.reload();;
								$('#tampilsewing').load("apps/Transaction/Receiving/sourcedatasewing.php");
								$('#cekwip').load("apps/Transaction/Receiving/cekwip.php?cm=" + cmts + "&co=" + color + "&in=" + inseams + "&si=" + sizes + "&ex=" + exftydate + "&saw=" + saw);
								$('#cart').load("apps/Transaction/Receiving/cart.php");
								$('#conf').val('');
								$('#loading-off2').click();
								$('#nocmt').val('');
								$('#nocolor').val('');
								$('#noinseams').val('');
								$('#nosizes').val('');
								$('#exftydate').val('');
								$('#exftydateasli').val('');
								$('#qty_in').val('');
								$('#wo_no').val(cmt);
								$('#garment_colors').val(colors);
								$('#ex_fty_date').val(exdate);
							}
						});
					}
				});
		}
	}

	function hapuscart(a, wo, colors, inseams, sizes) {
		$('#hpsmodcart').val("hpscart");
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url: "apps/Transaction/Receiving/simpan.php?id=" + a,
			data: data,
			success: function() {
				$('#tampilmodcart').load("apps/Transaction/Receiving/sourcedatamodcart.php?reload=1");
				$('#tampilsewing').load("apps/Transaction/Receiving/sourcedatasewing.php");
				$('#cart').load("apps/Transaction/Receiving/cart.php");
				$('#hpsmodcart').val('');
				$('#cekwip').load("apps/Transaction/Receiving/cekwip.php?cm=" + cmts + "&co=" + color + "&in=" + ins + "&si=" + siz);
			}
		});
	}

	function saverec() {
		if ($('#username_1').val() == '') {
			swal({
				icon: 'info',
				title: 'Fill User',
				text: 'Must Fill the User',
				timer: 1000,
			});
			$('#username_1').focus();
		} else {
			//if ($('#idcek').val() > 0) {
			if ($('#balance_1').val() <= 0) {
				if ($('#idlast').val() == 1) {
					if ($('#note_1').val() != '') {
						$('#confmodcart').val('simpan');
						var data = $('.form-user').serialize();
						$.ajax({
							type: 'POST',
							url: "apps/Transaction/Receiving/simpan.php",
							data: data,
							success: function() {
								swal("Saved!", {
									icon: "success",
								});
								//javascript: window.location.href="content.php?p="+$('#getpmodcart').val();
							}
						});
					} else {
						swal({
							icon: 'info',
							title: 'Fill Note',
							text: 'Receive Qty Less than Cutting Qty',
							footer: '<a href>Why do I have this issue?</a>'
						});
					}
				} else if ($('#idlast').val() == 2) {
					swal({
						icon: 'error',
						title: 'Can not Submit!!',
						text: 'Receive Qty More than Cutting Qty',
						footer: '<a href>Why do I have this issue?</a>'
					});
				} else {
					swal({
							title: "Are You Sure?",
							text: "Save Receiving",
							icon: "warning",
							buttons: true,
							dangerMode: true,
						})
						.then((willDelete) => {
							if (willDelete) {
								$('#confmodcart').val('simpan');
								var data = $('.form-user').serialize();
								$.ajax({
									type: 'POST',
									url: "apps/Transaction/Receiving/simpan.php",
									data: data,
									success: function(lot) {
										alert("do");
										datatableInit();
										explot = lot.split('_');
										swal({
											icon: 'success',
											title: 'Lot No : ' + explot[0],
											text: 'Saved',
											footer: '<a href>Why do I have this issue?</a>'
										}).then((willoke) => {
											javascript: window.open("lib/pdf-qrcode.php?c=" + explot[1], "_blank", "width=700,height=450");
											javascript: window.location.href = "content.php?p=" + $('#getpmodcart').val();
										});

									}
								});
							}
						});
				}
			} else {
				swal({
					icon: 'error',
					title: 'Can not Submit!!',
					text: 'Receive Qty More than Cutting Qty',
					footer: '<a href>Why do I have this issue?</a>'
				});
			}
		}
	}

	$(function() {
		//$('#nocmt').focus();
		$("#nocmt").autocomplete({
			source: function(request, response) {
				$.getJSON(
					"apps/Transaction/Receiving/sourcedata.php", {
						d: '1',
						term: request.term,
						scolor: $('#nocolor').val(),
						sins: $('#noinseams').val(),
						ssiz: $('#nosizes').val(),
						exdate: $('#exftydate').val()
					},
					response
				);
			},
			minLength: 2,
			select: function(event, ui) {
				if (ui.item != undefined) {
					$(this).val(ui.item.value);
					//$('#selected_id').val(ui.item.id);
					$("#idcmt").val(ui.item.id);
					//depar(ui.item.id_departemen);
				}
				return false;
			}
		});

	});

	$(function() {
		$("#nocolor").autocomplete({
			source: function(request, response) {
				$.getJSON(
					"apps/Transaction/Receiving/sourcedata.php", {
						d: '2',
						term: request.term,
						scmt: $('#nocmt').val(),
						sins: $('#noinseams').val(),
						ssiz: $('#nosizes').val(),
						exdate: $('#exftydate').val()
					},
					response
				);
			},
			minLength: 0,
			select: function(event, ui) {
				if (ui.item != undefined) {
					$(this).val(ui.item.value);
					$("#idcolor").val(ui.item.id);
				}
				return false;
			}
		}).focus(function() {
			$(this).autocomplete("search", "");
		});
	});

	$(function() {

		$("#noinseams").autocomplete({
			source: function(request, response) {
				$.getJSON(
					"apps/Transaction/Receiving/sourcedata.php", {
						d: '3',
						term: request.term,
						scmt: $('#nocmt').val(),
						scolor: $('#nocolor').val(),
						ssiz: $('#nosizes').val(),
						exdate: $('#exftydate').val()
					},
					response
				);
			},
			minLength: 0,
			select: function(event, ui) {
				//alert($('#idcmt').val());
				if (ui.item != undefined) {
					//alert('as');
					//alert(ui.item.id_value);
					$(this).val(ui.item.value);
					//$('#selected_id').val(ui.item.id);
					$("#idcolor").val(ui.item.id);
					//depar(ui.item.id_departemen);
				}
				return false;
			}
		}).focus(function() {
			$(this).autocomplete("search", "");
		});

	});

	$(function() {

		$("#nosizes").autocomplete({
			source: function(request, response) {
				$.getJSON(
					"apps/Transaction/Receiving/sourcedata.php", {
						d: '4',
						term: request.term,
						scmt: $('#nocmt').val(),
						sins: $('#noinseams').val(),
						scolor: $('#nocolor').val(),
						exdate: $('#exftydate').val()
					},
					response
				);
			},
			minLength: 0,
			select: function(event, ui) {
				//alert($('#idcmt').val());
				if (ui.item != undefined) {
					//alert('as');
					//alert(ui.item.id_value);
					$(this).val(ui.item.value);
					//$('#selected_id').val(ui.item.id);
					$("#idcolor").val(ui.item.id);
					//depar(ui.item.id_departemen);
				}
				return false;
			}
		}).focus(function() {
			$(this).autocomplete("search", "");
		});

	});

	$(function() {
		$("#exftydate").autocomplete({
			source: function(request, response) {
				$.getJSON(
					"apps/Transaction/Receiving/sourcedata.php", {
						d: '5',
						term: request.term,
						scmt: $('#nocmt').val(),
						scolor: $('#nocolors').val(),
						ssiz: $('#nosizes').val(),
						sins: $('#noinseams').val()
					},
					response
				);
			},
			minLength: 0,
			select: function(event, ui) {
				if (ui.item != undefined) {
					$(this).val(ui.item.value);
					//$( "#idcolor" ).val( ui.item.id );
					$("#exftydateasli").val(ui.item.exftydateasli);
				}
				return false;
			}
		}).focus(function() {
			$(this).autocomplete("search", "");
		});
	});


	$(function() {
		var will = $('#willrec_' + id).val();
		var cut = $('#cutqty_' + id).val();
		var jum = parseInt(cut) - parseInt(cut);
		$('#idcek').val(1);
	});

	function up(id, seq, sub) {
		$('#naik').val(seq);
		$('#idnya').val(id);
		$('#sub').val(sub);
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url: "apps/Transaction/sequence/simpan.php",
			data: data,
			success: function() {
				$('#tampildata').load("apps/Transaction/sequence/tampil.php");
				$('#naik').val('');
				$('#idnya').val('');
			}
		});


		//$('#id').val(id);
		//javascript: document.getElementById('form_index').submit();
		//javascript: window.location.href="index.php?x=berita_e&id="+id;
		//alert(id);

	}

	function down(id, seq, sub) {
		$('#turun').val(seq);
		$('#idnya').val(id);
		$('#sub').val(sub);
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url: "apps/Transaction/sequence/simpan.php",
			data: data,
			success: function() {
				$('#tampildata').load("apps/Transaction/sequence/tampil.php");
				$('#turun').val('');
				$('#idnya').val('');
			}
		});
	}

	function cekwip(id) {
		// Check
		var jm = $('#jmlwip').val();
		for (i = 1; i <= jm; i++) {
			//alert(id.checked);
			if (id.checked) {
				$("#cmtno_" + i).prop("checked", true);
			} else {
				$("#cmtno_" + i).prop("checked", false);
			}
		}
	}


	function cekwo(id) {
		var jm = $('#jmlwo').val();
		for (i = 1; i <= jm; i++) {
			//alert(id.checked);
			if (id.checked) {
				$("#wono_" + i).prop("checked", true);
			} else {
				$("#wono_" + i).prop("checked", false);
			}
		}

	}

	function cekapp(id) {
		var jm = $('#jmlappwo').val();
		for (i = 1; i <= jm; i++) {
			//alert(id.checked);
			if (id.checked) {
				$("#appwo_" + i).prop("checked", true);
				$("#appwo2_" + i).val(1);
			} else {
				$("#appwo_" + i).prop("checked", false);
				$("#appwo2_" + i).val(0);
			}
		}

	}

	function cekapp2(a, b) {
		if (a.checked) {
			$("#appwo2_" + b).val(1);
		} else {
			$("#appwo2_" + b).val(0);
		}
	}

	function savewo(a) {
		$('#cmtwo').val(a);
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url: "apps/Transaction/sequence/simpan.php",
			data: data,
			success: function() {
				$('#tampilwo').load("apps/Transaction/sequence/tampilwo.php");
				$('#tampilwip').load("apps/Transaction/sequence/tampilwip.php?b=" + $('#no_buyer').val() + "&s=" + $('#no_style').val() + "&cm=" + $('#no_cmt').val() + "&co=" + $('#no_color').val() + "&tgl=" + $('#tanggal1').val() + "&tgl2=" + $('#tanggal2').val());
				$('#checkwip').prop("checked", false);
				$('#checkwo').prop("checked", false);
				$('#tampilapp').load("apps/Transaction/sequence/tampilapp.php?b=" + $('#no_buyer').val() + "&s=" + $('#no_style').val() + "&cm=" + $('#no_cmt').val() + "&co=" + $('#no_color').val() + "&tgl=" + $('#tanggal1').val() + "&tgl2=" + $('#tanggal2').val());
			}
		});
	}

	function hapusker(a) {
		$('#cmtwo').val(a);
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url: "apps/Transaction/sequence/simpan.php",
			data: data,
			success: function() {
				$('#tampilwo').load("apps/Transaction/sequence/tampilwo.php");
				$('#tampilwip').load("apps/Transaction/sequence/tampilwip.php?b=" + $('#no_buyer').val() + "&s=" + $('#no_style').val() + "&cm=" + $('#no_cmt').val() + "&co=" + $('#no_color').val() + "&tgl=" + $('#tanggal1').val() + "&tgl2=" + $('#tanggal2').val());
				$('#tampilapp').load("apps/Transaction/sequence/tampilapp.php");
				$('#checkwip').prop("checked", false);
				$('#checkwo').prop("checked", false);
			}
		});
	}

	function hapusproses(a, b, c) {
		$('#cmtwo2').val(3);
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url: "apps/Transaction/sequence/simpan.php?d=" + a + "&e=" + b + "&f=" + c,
			data: data,
			success: function() {
				$('#tampildata').load("apps/Transaction/sequence/tampil.php");
				$('#turun').val('');
				$('#idnya').val('');
			}
		});
	}

	function hapus(id) {
		$('#id').val(id);
		$('#aksi').val('hapus');
		javascript: document.getElementById('form_index').submit();

	}

	function hitungcut(val, id) {
		$('#cutqty_' + id).val();
		$('#willrec_' + id).val();
		var jumbalance = parseInt($('#willrec_' + id).val()) - parseInt($('#cutqty_' + id).val());
		$('#balance_' + id).val(jumbalance);
	}

	function hitungin(a, id) {
		//alert($('#qtysend_'+id).val());
		if (parseInt($('#qtyin_' + id).val()) > parseInt($('#qtysend_' + id).val())) {
			swal({
				icon: 'error',
				title: 'Qty Over',
				text: 'Receive Qty More than Send Qty',
				footer: '<a href>Why do I have this issue?</a>'
			});
			//$('#qtyin_'+id).val($('#qtysend_'+id).val());
		}

	}

	function tooltip(val, id) {

		var will = $('#willrec_' + id).val();
		var cut = $('#cutqty_' + id).val();
		var jum = parseInt(cut) - parseInt(cut);
		if (val.checked) {
			if (parseInt(will) > parseInt(cut)) {
				swal({
					icon: 'error',
					title: 'Can not Submit!!',
					text: 'Receive Qty More than Cutting Qty',
					footer: '<a href>Why do I have this issue?</a>'
				});
				$('#sumbit').hide();
				$('#lastrec_' + id).prop("checked", false);
				$('#idlast').val(2);
			} else if (parseInt(will) < parseInt(cut)) {
				swal({
					icon: 'info',
					title: 'Fill Note',
					text: 'Receive Qty Less than Cutting Qty',
					footer: '<a href>Why do I have this issue?</a>'
				});
				$('#sumbit').show();
				$('#idlast').val(1);
			} else {
				$('#sumbit').show();
				$('#idlast').val(0);
			}
		} else {
			$('#idlast').val(0);
		}
		//javascript: document.getElementById('form_index').submit();
		//javascript: window.location.href="index.php?x=berita_e&id="+id;
		//alert(id);F

	}


	function call(id) {
		var expl = id.split('_');
		call2(expl[1])
	}

	function call2(st) {
		//alert(st);
		if (st == 1) {
			$('#st').show();
		}
		if (st == 0) {
			$('#st').hide();
		}
	}

	function validate_frm() {

		try {
			x = document.formku;


			if (x.password2.value != x.password.value) {
				alert('Password Tidak sama!');
				x.password2.value = '';
				x.password2.focus();
				return (false);
			}
			return (true);
		} catch (e) {
			alert('Error ' + e.description);
		}
	}


	function saveedit() {
		$('#codeproc').val("edit");
	}

	function hapusroledtl(a, b, c, d) {
		$('#hpsdtl').val(a);
		$('#codeproc').val("hapusdtl");
		var data = $('.form-user').serialize();
		$.ajax({
			type: 'POST',
			url: "apps/Transaction/sequence/simpan.php?id=" + b + "&seq=" + d,
			data: data,
			success: function() {
				$('#tampildata').load("apps/Transaction/sequence/tampil.php");
				$('#proc').load("apps/Transaction/sequence/sourceprocessedit2.php?id=" + b + "&j=" + c + "&seq=" + d);
				$('#hpsdtl').val('');
				//$('#idnya').val('');
				$('#codeproc').val("");
			}
		});
	}
</script>