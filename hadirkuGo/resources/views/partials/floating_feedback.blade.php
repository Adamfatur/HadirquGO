<a href="{{ route('feedback.index') }}" class="floating-feedback-btn">
    <i class="fas fa-comment-dots me-2"></i> Feedback
</a>

<style>
    .floating-feedback-btn {
        position: fixed;
        bottom: 85px; /* Sedikit lebih tinggi agar tidak menabrak bottom bar */
        right: 20px;
        padding: 10px 20px;
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        color: white;
        border-radius: 30px;
        text-decoration: none;
        z-index: 1050;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 4px 15px rgba(30, 58, 138, 0.4);
        display: flex;
        align-items: center;
        font-weight: 600;
        font-size: 14px;
        animation: bounceInFeedback 1s;
    }
    .floating-feedback-btn:hover {
        transform: translateY(-5px) scale(1.05);
        color: white;
        background: linear-gradient(135deg, #3b82f6, #1e3a8a);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.6);
    }
    .floating-feedback-btn i {
        font-size: 1.1rem;
    }
    @keyframes bounceInFeedback {
        0% { transform: scale(0.1); opacity: 0; }
        60% { transform: scale(1.15); opacity: 1; }
        100% { transform: scale(1); }
    }
    
    /* Responsive adjustment for very small screens */
    @media (max-width: 480px) {
        .floating-feedback-btn {
            bottom: 90px;
            right: 15px;
            padding: 8px 16px;
            font-size: 13px;
        }
    }
</style>
