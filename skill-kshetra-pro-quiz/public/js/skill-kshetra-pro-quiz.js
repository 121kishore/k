(function () {
  function grade(percent) {
    if (percent >= 90) return 'A+';
    if (percent >= 80) return 'A';
    if (percent >= 70) return 'B';
    if (percent >= 60) return 'C';
    return 'Needs Practice';
  }

  function updateProgress(quiz, current, total) {
    var percent = total ? Math.round((current / total) * 100) : 0;
    quiz.querySelector('.sqp-progress span').style.width = percent + '%';
    quiz.querySelector('.sqp-progress-label').textContent = percent + '%';
  }

  document.addEventListener('click', function (event) {
    var option = event.target.closest('.sqp-option');
    var next = event.target.closest('.sqp-next');
    var quiz = event.target.closest('.sqp-quiz');
    if (!quiz) return;

    var questions = Array.prototype.slice.call(quiz.querySelectorAll('.sqp-question'));
    var active = quiz.querySelector('.sqp-question.is-active');
    quiz._answers = quiz._answers || [];

    if (option && active) {
      var selected = option.textContent.trim();
      var correct = option.dataset.correct === '1';
      var correctAnswer = active.dataset.correctAnswer || '';
      var explanation = active.dataset.explanation || '';
      active.querySelectorAll('.sqp-option').forEach(function (button) {
        button.disabled = true;
        if (button.dataset.correct === '1') button.classList.add('is-correct');
      });
      option.classList.add(correct ? 'is-correct' : 'is-wrong');
      active.classList.add(correct ? 'sqp-flash-green' : 'sqp-flash-red');
      active.querySelector('.sqp-feedback').innerHTML = correct
        ? '<strong>✔ Correct</strong><p>' + explanation + '</p>'
        : '<strong>❌ Wrong</strong><p>You Selected: ' + selected + '</p><p>Correct Answer: ' + correctAnswer + '</p><p>' + explanation + '</p>';
      active.querySelector('.sqp-next').hidden = false;
      quiz._answers[Number(active.dataset.questionIndex)] = { question: active.querySelector('h3').textContent, selected: selected, correct: correctAnswer, explanation: explanation, isCorrect: correct };
    }

    if (next && active) {
      var currentIndex = Number(active.dataset.questionIndex);
      active.classList.remove('is-active');
      if (questions[currentIndex + 1]) {
        questions[currentIndex + 1].classList.add('is-active');
        updateProgress(quiz, currentIndex + 1, questions.length);
      } else {
        var correctCount = quiz._answers.filter(function (answer) { return answer && answer.isCorrect; }).length;
        var percent = Math.round((correctCount / questions.length) * 100);
        updateProgress(quiz, questions.length, questions.length);
        quiz.querySelector('.sqp-result').hidden = false;
        quiz.querySelector('[data-sqp-score]').textContent = correctCount + '/' + questions.length + ' (' + percent + '%)';
        quiz.querySelector('[data-sqp-correct]').textContent = correctCount;
        quiz.querySelector('[data-sqp-wrong]').textContent = questions.length - correctCount;
        quiz.querySelector('[data-sqp-rank]').textContent = grade(percent);
      }
    }
  });
}());
