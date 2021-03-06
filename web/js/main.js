/**
 * Valide du JSON
 * @param json
 */
function isValidJSON(json) {
	if (typeof json !== 'object') {
        try {
            JSON.parse(json);
        }
        catch (err) {
            console.log(err);
            return false;
        }
    }
    return true;
}

/**
 * Change la taille de la modale
 * @param size modal-lg | modal-sm
 */
function setModalSize(size) {
	$('#modal-dialog').addClass(size);
}

/**
 * Change le titre de la modale
 * @param title
 */
function setModalTitle(title) {
	$('#modal-title').append(title);
}

/**
 * Change le contenu de la modale
 * @param body
 */
function setModalBody(body) {
	$('#modal-body').append(body);
}

/**
 * Remet à zéro la modale
 */
function cleanModal() {
	$('#modal-dialog').removeClass().addClass('modal-dialog');
	$('#modal-title').empty();
	$('#modal-body').empty();
}

// Quand la modale a été cachée, on exécute la fonction
$('#modal').on('hidden.bs.modal', cleanModal);

/**
 * Récupère les gloses d'un mot ambigu et les en <option> d'un <select>
 * @param select
 * @param motAmbigu
 * @param callback
 */
function getGloses(select, motAmbigu, callback) {
	select.html('<option selected disabled value>Choisissez une glose (...)</option>');
	// Empêche la sélection pendant le chargement
	select.attr('disabled', 'disabled').addClass('loading');
	var url = Routing.generate('api_gloses_mot_ambigu_show');
	$.post(url, {motAmbigu: motAmbigu}, function (data) {
		var indication = "";
		if (data.links.length > 1)
			indication = data.links.length + ' existantes';
		else
			indication = data.links.length + ' existante';
		select.html('<option selected disabled value>Choisissez une glose (' + indication + ')</option>');
		$.each(data.links, function (index) {
			select.append('<option value="' + data.links[index].id + '">' + data.links[index].valeur + '</option>');
		});
		select.removeAttr('disabled').removeClass('loading');

		// Appel la fonction de callback si elle existe
		typeof callback === 'function' && callback();
	}, "json");
}

/**
 * Affiche un message dans la modale pour dire qu'il faut se connecter
 */
function messageNeedConnectionModal() {
	setModalBody('<div class="alert alert-danger">Il faut être connecté pour utiliser cette fonctionnalité</div>');
}

function getPoints() {
	return parseInt($('#points').data('value'));
}
function updatePoints(points) {
	var newVal = getPoints() + parseInt(points);
	$('#points').html(formatNumber(newVal)).data('value', newVal);
}

function getCredits() {
	return parseInt($('#credits').data('value'));
}
function updateCredits(credits) {
	var newVal = getCredits() + parseInt(credits);
	$('#credits').html(formatNumber(newVal)).data('value', newVal);
}

function formatNumber(nStr) {
	nStr += '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(nStr)) {
		nStr = nStr.replace(rgx, '$1' + ' ' + '$2');
	}
	return nStr;
}

$(document).ready(function () {

	$.ajaxSetup({cache: true});

	// Active les tooltips bootstrap
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    // Si l'utilisateur n'a pas explicitement accepté les cookies, on affiche la modale
    if (!Cookies.get('cookieInfo')) {
		$('#cookieModal').modal({backdrop: 'static', keyboard: false});
	}

	// Au survol d'une réponse on surligne le MA
	$('body').on('mouseenter', '.reponseGroupe', function () {
		var ordre = $(this).attr('id').replace(/rep/g, '');
		$(this).css('background', 'rgba(160, 210, 51, 0.6)');
		$('amb#ma' + ordre).css('background', 'rgba(160, 210, 51, 0.6)');
		$('amb#' + ordre).css('background', 'rgba(160, 210, 51, 0.6)');
	});
	$('body').on('mouseleave', '.reponseGroupe', function () {
		var ordre = $(this).attr('id').replace(/rep/g, '');
		$(this).removeAttr('style');
		$('amb#ma' + ordre).removeAttr('style');
		$('amb#' + ordre).removeAttr('style');
	});

	// Au survol d'un MA on surligne la réponse
	$('body').on('mouseenter', 'amb', function () {
		var ordre = $(this).attr('id').replace(/ma/g, '');
		$(this).css('background', 'rgba(160, 210, 51, 0.6)');
		$('#rep' + ordre).css('background', 'rgba(160, 210, 51, 0.6)');
	});
	$('body').on('mouseleave', 'amb', function () {
		var ordre = $(this).attr('id').replace(/ma/g, '');
		$(this).removeAttr('style');
		$('#rep' + ordre).removeAttr('style');
	});

	for (notify in notifies) {
		$.notify({
			icon: notifies[notify]['icon'],
			title: notifies[notify]['title'],
			message: notifies[notify]['message'],
			url: notifies[notify]['url']
		},{
			type: notifies[notify]['type'],
			mouse_over: 'pause',
			template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0} text-center" role="alert">' +
				'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
				'<span data-notify="icon" class="size24 color-gold"></span> ' +
				'<span data-notify="title"><b>{1}</b></span><br> ' +
				'<span data-notify="message">{2}</span>' +
				'<div class="progress" data-notify="progressbar">' +
				'<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
				'</div>' +
				'<a href="{3}" target="{4}" data-notify="url"></a>' +
				'</div>'
		});
	}

});
