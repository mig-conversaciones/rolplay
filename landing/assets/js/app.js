document.addEventListener('alpine:init', () => {
    Alpine.data('simulationDemo', () => ({
        step: 0,
        score: {
            communication: 50,
            leadership: 50,
            teamwork: 50
        },
        feedback: null,
        
        init() {
            // Intro animation or setup logic
        },

        chooseOption(optionAttributes) {
            // Apply score changes
            this.score.communication = Math.min(100, Math.max(0, this.score.communication + optionAttributes.comm));
            this.score.leadership = Math.min(100, Math.max(0, this.score.leadership + optionAttributes.lead));
            this.score.teamwork = Math.min(100, Math.max(0, this.score.teamwork + optionAttributes.team));
            
            // Set feedback
            this.feedback = optionAttributes.feedbackMsg;
            
            // Advance step (simple toggle for demo)
            setTimeout(() => {
                this.step++;
            }, 600);
        },

        resetDemo() {
            this.step = 0;
            this.score = { communication: 50, leadership: 50, teamwork: 50 };
            this.feedback = null;
        }
    }));
});
