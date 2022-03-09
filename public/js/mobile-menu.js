var blockHabits = document.getElementById('habits');
var blockTask = document.getElementById('task');
var blockCharacter = document.getElementById('character');

function showBlock(blockName) {
  if (blockName === 'habits') {
    blockTask.style.display = 'none';
    blockCharacter.style.display = 'none';
    blockHabits.style.display = 'block';
		document.getElementsByClassName('habits-header').querySelector.add("m-header-mobile");
		document.getElementsByClassName('header-tasks').querySelector.remove("m-header-mobile");
		document.getElementsByClassName('header-mobile').querySelector.remove("m-header-mobile");
  } else if (blockName === 'task') {
    blockHabits.style.display = 'none';
    blockCharacter.style.display = 'none';
    blockTask.style.display = 'block';
		document.getElementsByClassName('habits-header').querySelector.remove("m-header-mobile");
		document.getElementsByClassName('header-tasks').querySelector.add("m-header-mobile");
		document.getElementsByClassName('header-mobile').querySelector.remove("m-header-mobile");
  } else if (blockName === 'character') {
    blockHabits.style.display = 'none';
    blockTask.style.display = 'none';
    blockCharacter.style.display = 'block';
		document.getElementsByClassName('habits-header').classList.remove('m-header-mobile');
		document.getElementsByClassName('header-tasks').classList.remove('m-header-mobile');
		document.getElementsByClassName('header-mobile').classList.add('m-header-mobile');
  }
}

function mobileMenu() {
	  document.getElementById("mob-nav-ul").classList.toggle("templates-show");
}

// function mobileHabits() {
// 		document.getElementById('habits').style.display = 'block';
// 		document.getElementById('task').style.display = 'none';
// 		document.getElementById('character').style.display = 'none';
//
// }
// function mobileTask() {
// 		document.getElementById('habits').style.display = 'none';
// 		document.getElementById('task').style.display = 'block';
// 		document.getElementById('character').style.display = 'none';
//
// }
// function mobileCharacter() {
//     document.getElementById('habits').style.display = 'none';
// 		document.getElementById('task').style.display = 'none';
// 		document.getElementById('character').style.display = 'block';
// }
