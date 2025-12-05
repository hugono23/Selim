import Phaser from 'phaser';

export class UrgencyGauge extends Phaser.GameObjects.Container {
    private background: Phaser.GameObjects.Rectangle;
    private label: Phaser.GameObjects.Text;
    private timerText: Phaser.GameObjects.Text;
    private timeLeft: number = 300; // 5 minutes default
    private timerEvent: Phaser.Time.TimerEvent;

    constructor(scene: Phaser.Scene) {
        super(scene, 0, 0);
        this.scene = scene;

        this.createUI();
        this.scene.add.existing(this);
        this.setDepth(90);

        this.startTimer();

        this.scene.scale.on('resize', this.layout, this);
        this.layout();
    }

    private createUI() {
        this.background = this.scene.add.rectangle(0, 0, 200, 60, 0x990000, 0.8);
        this.background.setStrokeStyle(2, 0xff0000);
        this.add(this.background);

        this.label = this.scene.add.text(0, -15, 'FIN DE SUPPORT WIN10', {
            fontFamily: 'Arial', fontSize: '12px', color: '#ffffff', fontStyle: 'bold'
        }).setOrigin(0.5);
        this.add(this.label);

        this.timerText = this.scene.add.text(0, 10, '05:00', {
            fontFamily: 'Arial', fontSize: '24px', color: '#ffffff', fontStyle: 'bold'
        }).setOrigin(0.5);
        this.add(this.timerText);
    }

    public layout() {
        const { width } = this.scene.scale;
        this.setPosition(width - 120, 50);
    }

    private startTimer() {
        this.timerEvent = this.scene.time.addEvent({
            delay: 1000,
            callback: this.updateTimer,
            callbackScope: this,
            loop: true
        });
    }

    private updateTimer() {
        if (this.timeLeft > 0) {
            this.timeLeft--;
            const minutes = Math.floor(this.timeLeft / 60);
            const seconds = this.timeLeft % 60;
            this.timerText.setText(`${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);
        } else {
            this.timerText.setText('00:00');
            this.timerText.setColor('#ff0000');
            this.scene.events.emit('urgency-timeout');
            this.timerEvent.remove();
        }
    }
}
