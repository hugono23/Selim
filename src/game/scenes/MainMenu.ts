import { Scene, GameObjects } from 'phaser';

export class MainMenu extends Scene {
    background: GameObjects.Image;
    logo: GameObjects.Image;
    title: GameObjects.Text;

    constructor() {
        super('MainMenu');
    }

    create() {
        this.background = this.add.image(1024, 512, 'background');


        this.title = this.add.text(1024, 300, 'Sélim The Game', {
            fontFamily: 'Pixeled', fontSize: 80, color: '#ffffff',
            stroke: '#000000', strokeThickness: 8,
            align: 'center'
        }).setOrigin(0.5);

        const playButton = this.add.image(1024, 560, 'play_button')
            .setInteractive({ useHandCursor: true });

        // Floating animation
        this.tweens.add({
            targets: playButton,
            y: '+=20',
            duration: 1500,
            yoyo: true,
            repeat: -1,
            ease: 'Sine.easeInOut'
        });

        // Glow effect for title
        this.title.postFX.addGlow(0xffffff, 4, 0, false, 0.1, 32);

        playButton.on('pointerover', () => {
            playButton.setTint(0xcccccc);
        });

        playButton.on('pointerout', () => {
            playButton.clearTint();
        });

        playButton.on('pointerdown', () => {
            this.scene.start('Game');
        });
    }
}
