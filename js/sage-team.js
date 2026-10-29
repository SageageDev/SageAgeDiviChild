/* Team grid (a section with CSS class sage-team, holding Team Member modules): click a team member to read the bio in a
   pop-up. The bio is the Team Member module's description, hidden in the grid by sage-team.css. */
(function () {
	var dialog;

	function open(member) {
		var bio = member.querySelector('.et_pb_team_member_description_content');
		if (!bio || !bio.textContent.trim()) return;
		if (!dialog) {
			dialog = document.createElement('dialog');
			dialog.className = 'sage-team-dialog';
			dialog.setAttribute('aria-labelledby', 'sage-team-dialog-name');
			dialog.addEventListener('click', function (e) { if (e.target === dialog) dialog.close(); });
			document.body.appendChild(dialog);
		}
		var img = member.querySelector('.et_pb_team_member_image img');
		var name = member.querySelector('.et_pb_module_header');
		var position = member.querySelector('.et_pb_member_position');
		dialog.innerHTML = '<button type="button" class="sage-team-close" aria-label="Close">&times;</button>' +
			'<div class="sage-team-photo">' + (img ? img.outerHTML : '') + '</div>' +
			'<div class="sage-team-text"><h3 id="sage-team-dialog-name">' + (name ? name.innerHTML : '') + '</h3>' +
			'<p class="sage-team-position">' + (position ? position.innerHTML : '') + '</p>' + bio.innerHTML + '</div>';
		dialog.querySelector('.sage-team-close').addEventListener('click', function () { dialog.close(); });
		dialog.showModal();
	}

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.sage-team .et_pb_team_member').forEach(function (member) {
			var bio = member.querySelector('.et_pb_team_member_description_content');
			if (!bio || !bio.textContent.trim()) return;
			member.classList.add('sage-has-bio');
			member.setAttribute('tabindex', '0');
			member.setAttribute('role', 'button');
			member.addEventListener('click', function () { open(member); });
			member.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(member); }
			});
		});
	});
})();
